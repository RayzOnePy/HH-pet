<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreResumeRequest;
use App\Http\Requests\UpdateResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Models\Resume;
use App\Models\ResumeContact;
use App\Models\ResumeEducation;
use App\Models\ResumeSkill;
use App\Models\ResumeWork;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ResumeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $resumes = Resume::with(['user', 'skills', 'works'])
            ->where('is_active', true)
            ->whereHas('user', function ($q) {
                $q->whereHas('roles', function ($r) {
                    $r->where('name', 'applicant');
                });
            })
            ->when($request->has('skill'), function ($query) use ($request) {
                $query->whereHas('skills', function ($q) use ($request) {
                    $q->where('skill', 'ilike', "%{$request->skill}%");
                });
            })
            ->when($request->has('title'), function ($query) use ($request) {
                $query->where('title', 'ilike', "%{$request->title}%");
            })
            ->when($request->has('salary_from'), function ($query) use ($request) {
                $query->where('salary', '>=', $request->salary_from);
            })
            ->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => ResumeResource::collection($resumes),
            'meta' => [
                'total' => $resumes->total(),
                'per_page' => $resumes->perPage(),
                'current_page' => $resumes->currentPage(),
                'last_page' => $resumes->lastPage(),
            ]
        ]);
    }

    public function show(Resume $resume): JsonResponse
    {
        if (!$resume->is_active) {
            return response()->json([
                'message' => 'Резюме не найдено'
            ], 404);
        }

        $resume->load(['user', 'contacts', 'skills', 'works', 'educations.degree']);

        return response()->json([
            'data' => new ResumeResource($resume)
        ]);
    }

    public function myResume(): JsonResponse
    {
        $user = Auth::user();
        $resume = $user->resume;

        if (!$resume) {
            return response()->json([
                'message' => 'Резюме не найдено',
                'data' => null
            ], 404);
        }

        $resume->load(['contacts', 'skills', 'works', 'educations.degree']);

        return response()->json([
            'data' => new ResumeResource($resume)
        ]);
    }

    public function store(StoreResumeRequest $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user->hasRole('applicant')) {
            return response()->json([
                'message' => 'Только соискатели могут создавать резюме'
            ], 403);
        }

        if ($user->resume) {
            return response()->json([
                'message' => 'У вас уже есть резюме'
            ], 409);
        }

        DB::beginTransaction();

        try {
            $resume = Resume::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'salary' => $request->salary,
                'is_active' => $request->is_active ?? true,
            ]);

            // График работы
            if ($request->has('work_schedule_ids')) {
                $resume->workSchedules()->sync($request->work_schedule_ids);
            }

            // Контакты
            if ($request->has('contacts')) {
                foreach ($request->contacts as $contact) {
                    ResumeContact::create([
                        'resume_id' => $resume->id,
                        'type' => $contact['type'],
                        'value' => $contact['value'],
                    ]);
                }
            }

            // Навыки
            if ($request->has('skills')) {
                foreach ($request->skills as $skill) {
                    ResumeSkill::create([
                        'resume_id' => $resume->id,
                        'skill' => $skill['skill'],
                        'level' => $skill['level'],
                    ]);
                }
            }

            // Опыт работы
            if ($request->has('work_experiences')) {
                foreach ($request->work_experiences as $work) {
                    ResumeWork::create([
                        'resume_id' => $resume->id,
                        'title' => $work['title'],
                        'experience_summary' => $work['experience_summary'],
                        'start_date' => $work['start_date'],
                        'end_date' => $work['end_date'] ?? null,
                        'is_current' => $work['is_current'] ?? false,
                    ]);
                }
            }

            // Образование
            if ($request->has('educations')) {
                foreach ($request->educations as $education) {
                    ResumeEducation::create([
                        'resume_id' => $resume->id,
                        'institution' => $education['institution'],
                        'faculty' => $education['faculty'],
                        'specialty' => $education['specialty'],
                        'qualification' => $education['qualification'],
                        'degree_id' => $education['degree_id'],
                        'start_date' => $education['start_date'],
                        'end_date' => $education['end_date'] ?? null,
                        'is_current' => $education['is_current'] ?? false,
                    ]);
                }
            }

            DB::commit();

            $resume->load(['contacts', 'skills', 'works', 'educations.degree', 'workSchedules']);

            return response()->json([
                'message' => 'Резюме успешно создано',
                'data' => new ResumeResource($resume)
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating resume: ' . $e->getMessage());

            return response()->json([
                'message' => 'Ошибка при создании резюме'
            ], 500);
        }
    }

    public function toggleActive(): JsonResponse
    {
        $user = Auth::user();
        $resume = $user->resume;

        if (!$resume) {
            return response()->json([
                'message' => 'Резюме не найдено'
            ], 404);
        }

        $resume->is_active = !$resume->is_active;
        $resume->save();

        return response()->json([
            'message' => $resume->is_active ? 'Резюме опубликовано' : 'Резюме скрыто',
            'data' => [
                'is_active' => $resume->is_active
            ]
        ]);
    }


    public function update(UpdateResumeRequest $request): JsonResponse
    {
        $user = Auth::user();
        $resume = $user->resume;

        if (!$resume) {
            return response()->json([
                'message' => 'Резюме не найдено'
            ], 404);
        }

        DB::beginTransaction();

        try {
            $resume->update($request->only([
                'title', 'salary', 'is_active'
            ]));

            // График работы
            if ($request->has('work_schedule_ids')) {
                $resume->workSchedules()->sync($request->work_schedule_ids);
            }

            // Контакты
            if ($request->has('contacts')) {
                $this->syncContacts($resume, $request->contacts);
            }

            // Навыки
            if ($request->has('skills')) {
                $this->syncSkills($resume, $request->skills);
            }

            // Опыт работы
            if ($request->has('work_experiences')) {
                $this->syncWorkExperiences($resume, $request->work_experiences);
            }

            // Образование
            if ($request->has('educations')) {
                $this->syncEducations($resume, $request->educations);
            }

            DB::commit();

            $resume->load(['contacts', 'skills', 'works', 'educations.degree', 'workSchedules']);

            return response()->json([
                'message' => 'Резюме успешно обновлено',
                'data' => new ResumeResource($resume)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating resume: ' . $e->getMessage());

            return response()->json([
                'message' => 'Ошибка при обновлении резюме'
            ], 500);
        }
    }

    private function syncContacts(Resume $resume, array $newContacts): void
    {
        $currentIds = $resume->contacts->pluck('id')->toArray();

        $newIds = [];
        $contactsToCreate = [];

        foreach ($newContacts as $contact) {
            if (isset($contact['id'])) {
                $newIds[] = $contact['id'];
                $resume->contacts()->where('id', $contact['id'])->update([
                    'type' => $contact['type'],
                    'value' => $contact['value'],
                ]);
            } else {
                $contactsToCreate[] = $contact;
            }
        }

        $toDelete = array_diff($currentIds, $newIds);
        if (!empty($toDelete)) {
            $resume->contacts()->whereIn('id', $toDelete)->delete();
        }

        foreach ($contactsToCreate as $contact) {
            $resume->contacts()->create([
                'type' => $contact['type'],
                'value' => $contact['value'],
            ]);
        }
    }

    private function syncSkills(Resume $resume, array $newSkills): void
    {
        $currentIds = $resume->skills->pluck('id')->toArray();
        $newIds = [];
        $skillsToCreate = [];

        foreach ($newSkills as $skill) {
            if (isset($skill['id'])) {
                $newIds[] = $skill['id'];
                $resume->skills()->where('id', $skill['id'])->update([
                    'skill' => $skill['skill'],
                    'level' => $skill['level'],
                ]);
            } else {
                $skillsToCreate[] = $skill;
            }
        }

        $toDelete = array_diff($currentIds, $newIds);
        if (!empty($toDelete)) {
            $resume->skills()->whereIn('id', $toDelete)->delete();
        }

        foreach ($skillsToCreate as $skill) {
            $resume->skills()->create([
                'skill' => $skill['skill'],
                'level' => $skill['level'],
            ]);
        }
    }

    private function syncWorkExperiences(Resume $resume, array $newWorks): void
    {
        $currentIds = $resume->works->pluck('id')->toArray();
        $newIds = [];
        $worksToCreate = [];

        foreach ($newWorks as $work) {
            if (isset($work['id'])) {
                $newIds[] = $work['id'];
                $resume->works()->where('id', $work['id'])->update([
                    'title' => $work['title'],
                    'experience_summary' => $work['experience_summary'],
                    'start_date' => $work['start_date'],
                    'end_date' => $work['end_date'] ?? null,
                    'is_current' => $work['is_current'] ?? false,
                ]);
            } else {
                $worksToCreate[] = $work;
            }
        }

        $toDelete = array_diff($currentIds, $newIds);
        if (!empty($toDelete)) {
            $resume->works()->whereIn('id', $toDelete)->delete();
        }

        foreach ($worksToCreate as $work) {
            $resume->works()->create([
                'title' => $work['title'],
                'experience_summary' => $work['experience_summary'],
                'start_date' => $work['start_date'],
                'end_date' => $work['end_date'] ?? null,
                'is_current' => $work['is_current'] ?? false,
            ]);
        }
    }

    private function syncEducations(Resume $resume, array $newEducations): void
    {
        $currentIds = $resume->educations->pluck('id')->toArray();
        $newIds = [];
        $educationsToCreate = [];

        foreach ($newEducations as $education) {
            if (isset($education['id'])) {
                $newIds[] = $education['id'];
                $resume->educations()->where('id', $education['id'])->update([
                    'institution' => $education['institution'],
                    'faculty' => $education['faculty'],
                    'specialty' => $education['specialty'],
                    'qualification' => $education['qualification'],
                    'degree_id' => $education['degree_id'],
                    'start_date' => $education['start_date'],
                    'end_date' => $education['end_date'] ?? null,
                    'is_current' => $education['is_current'] ?? false,
                ]);
            } else {
                $educationsToCreate[] = $education;
            }
        }

        $toDelete = array_diff($currentIds, $newIds);
        if (!empty($toDelete)) {
            $resume->educations()->whereIn('id', $toDelete)->delete();
        }

        foreach ($educationsToCreate as $education) {
            $resume->educations()->create([
                'institution' => $education['institution'],
                'faculty' => $education['faculty'],
                'specialty' => $education['specialty'],
                'qualification' => $education['qualification'],
                'degree_id' => $education['degree_id'],
                'start_date' => $education['start_date'],
                'end_date' => $education['end_date'] ?? null,
                'is_current' => $education['is_current'] ?? false,
            ]);
        }
    }
}
