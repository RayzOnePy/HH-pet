<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
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
            'views_count' => $this->when(isset($this->views_count), $this->views_count, function() {
                return $this->views()->count();
            }),
            'responses_count' => $this->whenCounted('responses'),
            'favorites_count' => $this->whenCounted('favorites'),
            'has_responded' => $this->when($request->user(), function () use ($request) {
               return $this->responses()
                    ->where('candidate_id', $request->user()->id)
                   ->exists();
            }),
            'is_favorite' => $this->when($request->user(), function () use ($request) {
                return $this->favorites()
                    ->where('user_id', $request->user()->id)
                    ->exists();
            })
        ];
    }
}
