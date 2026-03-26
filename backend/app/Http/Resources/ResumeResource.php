<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResumeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'salary' => $this->salary,
            'can_business_trip' => $this->can_business_trip,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => [
                'id' => $this->user->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'middle_name' => $this->user->middle_name,
                'avatar_url' => $this->user->avatar_url,
            ],

            'contacts' => $this->whenLoaded('contacts', function () {
                return $this->contacts->map(fn($contact) => [
                    'id' => $contact->id,
                    'type' => $contact->type,
                    'value' => $contact->value,
                ]);
            }),

            'skills' => $this->whenLoaded('skills', function () {
                return $this->skills->map(fn($skill) => [
                    'id' => $skill->id,
                    'skill' => $skill->skill,
                    'level' => $skill->level,
                ]);
            }),

            'work_experiences' => $this->whenLoaded('works', function () {
                return $this->works->map(fn($work) => [
                    'id' => $work->id,
                    'title' => $work->title,
                    'experience_summary' => $work->experience_summary,
                    'start_date' => $work->start_date->format('Y-m-d'),
                    'end_date' => $work->end_date?->format('Y-m-d'),
                    'is_current' => $work->is_current,
                ]);
            }),

            'work_schedules' => $this->whenLoaded('workSchedules', function () {
                return $this->workSchedules->map(fn($schedule) => [
                    'id' => $schedule->id,
                    'name' => $schedule->name,
                ]);
            }),

            'educations' => $this->whenLoaded('educations', function () {
                return $this->educations->map(fn($education) => [
                    'id' => $education->id,
                    'institution' => $education->institution,
                    'faculty' => $education->faculty,
                    'specialty' => $education->specialty,
                    'qualification' => $education->qualification,
                    'degree' => [
                        'id' => $education->degree->id,
                        'name' => $education->degree->name,
                    ],
                    'start_date' => $education->start_date->format('Y-m-d'),
                    'end_date' => $education->end_date?->format('Y-m-d'),
                    'is_current' => $education->is_current,
                ]);
            }),
        ];
    }
}
