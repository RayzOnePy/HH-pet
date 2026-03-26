<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreVacancyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->companies()->exists();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'salary_from' => ['nullable', 'integer', 'min:0'],
            'salary_to' => ['nullable', 'integer', 'min:0', 'gte:salary_from'],
            'experience' => ['required', 'string', 'in:no,1-3,3-6,6+'],
            'city' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Название вакансии обязательно',
            'description.required' => 'Описание вакансии обязательно',
            'experience.required' => 'Укажите требуемый опыт работы',
            'city.required' => 'Укажите город',
            'salary_to.gte' => 'Зарплата "до" должна быть больше или равна зарплате "от"',
        ];
    }
}
