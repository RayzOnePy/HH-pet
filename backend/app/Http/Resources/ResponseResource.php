<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ResponseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'status_text' => $this->getStatusText(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'vacancy' => new VacancyResource($this->whenLoaded('vacancy')),
            'candidate' => [
                'id' => $this->candidate->id,
                'first_name' => $this->candidate->first_name,
                'last_name' => $this->candidate->last_name,
                'middle_name' => $this->candidate->middle_name,
                'avatar_url' => $this->candidate->avatar_url,
            ],
        ];
    }

    private function getStatusText(): string
    {
        return match($this->status) {
            'pending' => 'На рассмотрении',
            'invited' => 'Приглашен',
            'rejected' => 'Отклонен',
            default => $this->status,
        };
    }
}
