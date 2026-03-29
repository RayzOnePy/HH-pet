<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResponseResource;
use App\Models\Vacancy;
use App\Models\VacancyResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResponsesController extends Controller
{
    public function myResponses(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('applicant')) {
            return response()->json([
                'message' => 'Только соискатели могут просматривать свои отклики'
            ], 403);
        }

        $responses = $user->vacancyResponses()
            ->with('vacancy.company')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => ResponseResource::collection($responses),
            'meta' => [
                'total' => $responses->total(),
                'per_page' => $responses->perPage(),
                'current_page' => $responses->currentPage(),
                'last_page' => $responses->lastPage(),
            ]
        ]);
    }

    public function employerResponses(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('employer')) {
            return response()->json([
                'message' => 'Только работодатели могут просматривать отклики на свои вакансии'
            ], 403);
        }

        $company = $user->companies()->first();

        if (!$company) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'total' => 0,
                    'per_page' => 15,
                    'current_page' => 1,
                    'last_page' => 1,
                ]
            ]);
        }

        $query = VacancyResponse::with(['vacancy.company', 'candidate'])
            ->whereHas('vacancy', function ($q) use ($company) {
                $q->where('company_id', $company->id);
            })
            ->orderBy('created_at', 'desc');

        // Фильтр по статусу
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Фильтр по вакансии
        if ($request->has('vacancy_id') && $request->vacancy_id) {
            $query->where('vacancy_id', $request->vacancy_id);
        }

        $responses = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => ResponseResource::collection($responses),
            'meta' => [
                'total' => $responses->total(),
                'per_page' => $responses->perPage(),
                'current_page' => $responses->currentPage(),
                'last_page' => $responses->lastPage(),
            ]
        ]);
    }

    public function respond(Vacancy $vacancy): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('applicant')) {
            return response()->json([
                'message' => 'Только соискатели могут откликаться на вакансии'
            ], 403);
        }

        if (!$user->resume) {
            return response()->json([
                'message' => 'Сначала создайте резюме'
            ], 400);
        }

        if ($vacancy->responses()->where('candidate_id', $user->id)->exists()) {
            return response()->json([
                'message' => 'Вы уже откликнулись на эту вакансию'
            ], 409);
        }

        if ($vacancy->status !== 'active') {
            return response()->json([
                'message' => 'Эта вакансия больше не активна'
            ], 400);
        }

        DB::beginTransaction();

        try {
            $response = $vacancy->responses()->create([
                'candidate_id' => $user->id,
                'status' => 'pending'
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Отклик успешно отправлен',
                'data' => new ResponseResource($response->load('vacancy.company'))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error responding to vacancy: ' . $e->getMessage());

            return response()->json([
                'message' => 'Ошибка при отправке отклика'
            ], 500);
        }
    }

    public function cancelResponse(VacancyResponse $response): JsonResponse
    {
        $user = Auth::user();

        if ($response->candidate_id !== $user->id) {
            return response()->json([
                'message' => 'Это не ваш отклик'
            ], 403);
        }

        if ($response->status !== 'pending') {
            return response()->json([
                'message' => 'Нельзя отменить отклик, который уже рассмотрен'
            ], 400);
        }

        $response->delete();

        return response()->json([
            'message' => 'Отклик отменен'
        ]);
    }

    public function updateStatus(Request $request, VacancyResponse $response): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('employer')) {
            return response()->json([
                'message' => 'Только работодатели могут изменять статус откликов'
            ], 403);
        }

        $company = $user->companies()->first();
        if ($response->vacancy->company_id !== $company->id) {
            return response()->json([
                'message' => 'Это не ваша вакансия'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:pending,invited,rejected'
        ]);

        $oldStatus = $response->status;
        $response->status = $request->status;
        $response->save();

        return response()->json([
            'message' => 'Статус отклика изменен',
            'data' => new ResponseResource($response->load(['vacancy.company', 'candidate']))
        ]);
    }

    public function statistics(): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('employer')) {
            return response()->json([
                'message' => 'Только работодатели могут просматривать статистику'
            ], 403);
        }

        $company = $user->companies()->first();

        if (!$company) {
            return response()->json([
                'statistics' => [
                    'total' => 0,
                    'pending' => 0,
                    'invited' => 0,
                    'rejected' => 0,
                ]
            ]);
        }

        $statistics = VacancyResponse::whereHas('vacancy', function ($q) use ($company) {
            $q->where('company_id', $company->id);
        })
            ->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = "invited" THEN 1 ELSE 0 END) as invited,
            SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected
        ')
            ->first();

        return response()->json([
            'statistics' => [
                'total' => $statistics->total ?? 0,
                'pending' => $statistics->pending ?? 0,
                'invited' => $statistics->invited ?? 0,
                'rejected' => $statistics->rejected ?? 0,
            ]
        ]);
    }

    public function counts(): JsonResponse
    {
        $user = Auth::user();

        $counts = [
            'all' => $user->vacancyResponses()->count(),
            'pending' => $user->vacancyResponses()->where('status', 'pending')->count(),
            'invited' => $user->vacancyResponses()->where('status', 'invited')->count(),
            'rejected' => $user->vacancyResponses()->where('status', 'rejected')->count(),
        ];

        return response()->json([
            'counts' => $counts
        ]);
    }
}
