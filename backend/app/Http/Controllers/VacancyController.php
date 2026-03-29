<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVacancyRequest;
use App\Http\Requests\UpdateVacancyRequest;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VacancyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = Vacancy::with(['company', 'workSchedules'])
            ->withCount(['responses'])
            ->where('status', 'active');

        // Поиск
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        // Фильтр по опыту
        if ($request->has('experience') && $request->experience) {
            $query->where('experience', $request->experience);
        }

        // Фильтр по зарплате
        if ($request->has('salary_from') && $request->salary_from) {
            $query->where('salary_from', '>=', $request->salary_from);
        }

        if ($request->has('salary_to') && $request->salary_to) {
            $query->where(function ($q) use ($request) {
                $q->where('salary_to', '<=', $request->salary_to)
                    ->orWhereNull('salary_to');
            });
        }

        // Фильтр по графику работы
        if ($request->has('work_schedule_id') && $request->work_schedule_id) {
            $query->whereHas('workSchedules', function ($q) use ($request) {
                $q->where('work_schedule_id', $request->work_schedule_id);
            });
        }

        // Сортировка
        $sortField = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['created_at', 'salary_from'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortOrder);
        }

        $vacancies = $query->paginate($request->get('per_page', 15));

        if ($user) {
            $vacancyIds = $vacancies->pluck('id')->toArray();

            $favoriteIds = $user->favoriteVacancies()
                ->whereIn('vacancy_id', $vacancyIds)
                ->pluck('vacancy_id')
                ->toArray();

            $respondedIds = $user->vacancyResponses()
                ->whereIn('vacancy_id', $vacancyIds)
                ->pluck('vacancy_id')
                ->toArray();

            $viewedIds = $user->vacancyViews()
                ->whereIn('vacancy_id', $vacancyIds)
                ->pluck('vacancy_id')
                ->toArray();

            $vacancies->getCollection()->each(function ($vacancy) use ($favoriteIds, $respondedIds, $viewedIds) {
                $vacancy->setAttribute('is_favorite', in_array($vacancy->id, $favoriteIds));
                $vacancy->setAttribute('has_responded', in_array($vacancy->id, $respondedIds));
                $vacancy->setAttribute('is_viewed', in_array($vacancy->id, $viewedIds));
            });
        }

        return response()->json([
            'data' => VacancyResource::collection($vacancies),
            'meta' => [
                'total' => $vacancies->total(),
                'per_page' => $vacancies->perPage(),
                'current_page' => $vacancies->currentPage(),
                'last_page' => $vacancies->lastPage(),
            ]
        ]);
    }

    public function show(Vacancy $vacancy): JsonResponse
    {
        $user = Auth::user();

        $vacancy->addView($user);

        $vacancy->load('company');

        if ($user) {
            $vacancy->setAttribute('is_favorite', $user->favoriteVacancies()->where('vacancy_id', $vacancy->id)->exists());
            $vacancy->setAttribute('has_responded', $vacancy->responses()->where('candidate_id', $user->id)->exists());
            $vacancy->setAttribute('is_viewed', $vacancy->views()->where('user_id', $user->id)->exists());
        }

        return response()->json([
            'data' => new VacancyResource($vacancy)
        ]);
    }

    public function store(StoreVacancyRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $user = $request->user();
            $company = $user->companies()->first();

            $data['company_id'] = $company->id;
            $data['creator_id'] = $user->id;
            $data['status'] = 'active';

            $vacancy = Vacancy::create($data);

            DB::commit();

            return response()->json([
                'message' => 'Вакансия успешно создана',
                'data' => new VacancyResource($vacancy->load('company'))
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Ошибка при создании вакансии'
            ], 500);
        }
    }

    public function update(UpdateVacancyRequest $request, Vacancy $vacancy): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $vacancy->update($data);

            DB::commit();

            return response()->json([
                'message' => 'Вакансия успешно обновлена',
                'data' => new VacancyResource($vacancy->load('company'))
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Ошибка при обновлении вакансии'
            ], 500);
        }
    }

    public function destroy(Vacancy $vacancy): JsonResponse
    {
        $vacancy->delete();

        return response()->json([
            'message' => 'Вакансия удалена'
        ]);
    }

    public function restore($id): JsonResponse
    {
        $vacancy = Vacancy::withTrashed()->findOrFail($id);
        $vacancy->restore();

        return response()->json([
            'message' => 'Вакансия восстановлена',
            'data' => new VacancyResource($vacancy->load('company'))
        ]);
    }

    public function toggleStatus(Vacancy $vacancy): JsonResponse
    {
        $vacancy->status = $vacancy->status === 'active' ? 'inactive' : 'active';
        $vacancy->save();

        return response()->json([
            'message' => 'Статус вакансии изменен',
            'data' => new VacancyResource($vacancy)
        ]);
    }

    public function myVacancies(Request $request): JsonResponse
    {
        $user = $request->user();
        $company = $user->companies()->first();

        if (!$company) {
            return response()->json([
                'data' => [],
                'meta' => [
                    'total' => 0,
                    'per_page' => 15,
                    'current_page' => 1,
                    'last_page' => 1,
                ],
                'counts' => [
                    'total' => 0,
                    'active' => 0,
                    'inactive' => 0,
                ]
            ]);
        }

        $query = Vacancy::where('company_id', $company->id)
            ->with('company')
            ->withCount(['responses', 'favoritedBy']);

        $totalCount = (clone $query)->count();
        $activeCount = (clone $query)->where('status', 'active')->count();
        $inactiveCount = (clone $query)->where('status', 'inactive')->count();

        // Фильтр по статусу
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $vacancies = $query->paginate($request->get('per_page', 15));

        $vacancies->getCollection()->each(function ($vacancy) {
            $vacancy->views_count = $vacancy->views()->count();
        });

        return response()->json([
            'data' => VacancyResource::collection($vacancies),
            'meta' => [
                'total' => $vacancies->total(),
                'per_page' => $vacancies->perPage(),
                'current_page' => $vacancies->currentPage(),
                'last_page' => $vacancies->lastPage(),
            ],
            'counts' => [
                'total' => $totalCount,
                'active' => $activeCount,
                'inactive' => $inactiveCount,
            ]
        ]);
    }

    public function addToFavorites(Vacancy $vacancy): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('applicant')) {
            return response()->json([
                'message' => 'Только соискатели могут добавлять в избранное'
            ], 403);
        }

        if ($user->favoriteVacancies()->where('vacancy_id', $vacancy->id)->exists()) {
            return response()->json([
                'message' => 'Вакансия уже в избранном'
            ], 409);
        }

        $user->favoriteVacancies()->attach($vacancy->id);

        return response()->json([
            'message' => 'Вакансия добавлена в избранное'
        ], 201);
    }

    public function removeFromFavorites(Vacancy $vacancy): JsonResponse
    {
        $user = Auth::user();

        $user->favoriteVacancies()->detach($vacancy->id);

        return response()->json([
            'message' => 'Вакансия удалена из избранного'
        ]);
    }

    public function favorites(): JsonResponse
    {
        $user = Auth::user();

        $favorites = $user->favoriteVacancies()
            ->with('company')
            ->paginate(15);

        $vacancyIds = $favorites->pluck('id')->toArray();

        $respondedIds = $user->vacancyResponses()
            ->whereIn('vacancy_id', $vacancyIds)
            ->pluck('vacancy_id')
            ->toArray();

        $favorites->getCollection()->each(function ($vacancy) use ($respondedIds) {
            $vacancy->setAttribute('has_responded', in_array($vacancy->id, $respondedIds));
        });

        return response()->json([
            'data' => VacancyResource::collection($favorites),
            'meta' => [
                'total' => $favorites->total(),
                'per_page' => $favorites->perPage(),
                'current_page' => $favorites->currentPage(),
                'last_page' => $favorites->lastPage(),
            ]
        ]);
    }
}
