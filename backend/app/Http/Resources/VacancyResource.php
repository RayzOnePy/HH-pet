<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class VacancyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = Auth::user();

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'salary_from' => $this->salary_from,
            'salary_to' => $this->salary_to,
            'experience' => $this->experience,
            'city' => $this->city,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'company' => new CompanyResource($this->whenLoaded('company')),
            'work_schedules' => $this->whenLoaded('workSchedules', function () {
                return $this->workSchedules->map(fn($schedule) => [
                    'id' => $schedule->id,
                    'name' => $schedule->name,
                ]);
            }),

            'views_count' => $this->views_count ?? $this->views()->count(),
            'responses_count' => $this->responses_count ?? 0,
            'favorites_count' => $this->favorited_by_count ?? 0,

            'is_favorite' => $user ? $this->favoritedBy()->where('user_id', $user->id)->exists() : false,

            'has_responded' => $user ? $this->responses()->where('candidate_id', $user->id)->exists() : false,
        ];
    }
}
