<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return !$this->user()->companies()->exists();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название компании обязательно',
            'description.required' => 'Описание компании обязательно',
            'logo.image' => 'Логотип должен быть изображением',
            'logo.max' => 'Логотип не должен превышать 2MB',
        ];
    }
}
