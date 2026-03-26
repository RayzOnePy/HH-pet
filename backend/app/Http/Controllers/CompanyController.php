<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\CompanyRole;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index(): JsonResponse
    {
        $companies = Company::withCount('vacancies')->paginate(15);

        return response()->json([
            'data' => CompanyResource::collection($companies),
            'meta' => [
                'total' => $companies->total(),
                'per_page' => $companies->perPage(),
                'current_page' => $companies->currentPage(),
                'last_page' => $companies->lastPage(),
            ]
        ]);
    }

    public function show(Company $company): JsonResponse
    {
        $company->load('vacancies');

        return response()->json([
            'data' => new CompanyResource($company)
        ]);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('companies/logos', 'public');
                $data['logo_url'] = Storage::url($path);
            } else {
                $data['logo_url'] = null;
            }

            $company = Company::create($data);

            $ownerRole = CompanyRole::where('name', 'owner')->first();
            $company->users()->attach(Auth::id(), [
                'company_role_id' => $ownerRole->id,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Компания успешно создана',
                'data' => new CompanyResource($company)
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Ошибка при создании компании',
            ], 500);
        }
    }

    public function update(UpdateCompanyRequest $request, Company $company): JsonResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            if ($request->hasFile('logo')) {
                if ($company->logo_url) {
                    $oldPath = str_replace('/storage/', '', $company->logo_url);
                    Storage::disk('public')->delete($oldPath);
                }

                $path = $request->file('logo')->store('companies/logos', 'public');
                $data['logo_url'] = Storage::url($path);
            }

            $company->update($data);

            DB::commit();

            return response()->json([
                'message' => 'Компания успешно обновлена',
                'data' => new CompanyResource($company)
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function myCompany(): JsonResponse
    {
        $company = Auth::user()->company();

        if (!$company) {
            return response()->json([
                'message' => 'Компания не найдена',
            ], 404);
        }

        $company->load('vacancies');

        return response()->json([
           'data' => new CompanyResource($company)
        ]);
    }
}
