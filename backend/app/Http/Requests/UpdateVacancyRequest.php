<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $vacancy = $this->route('vacancy');

        $user = $this->user();
        $company = $user->companies()->first();

        return $company && $vacancy->company_id === $company->id;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'salary_from' => ['nullable', 'integer', 'min:0'],
            'salary_to' => ['nullable', 'integer', 'min:0', 'gte:salary_from'],
            'experience' => ['sometimes', 'string', 'in:no,1-3,3-6,6+'],
            'city' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'string', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'salary_to.gte' => 'Зарплата "до" должна быть больше или равна зарплате "от"',
        ];
    }
}
