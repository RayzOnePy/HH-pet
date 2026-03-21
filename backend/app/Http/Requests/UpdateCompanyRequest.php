<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $company = $this->route('company');

        return $company->users()
            ->where('user_id', $user->id)
            ->wherePivot('company_role_id', function($query) {
                $query->select('id')
                    ->from('company_roles')
                    ->whereIn('name', ['owner']);
            })->exists();
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'string'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.string' => 'Название должно быть строкой',
            'description.string' => 'Описание должно быть строкой',
            'logo.image' => 'Логотип должен быть изображением',
            'logo.max' => 'Логотип не должен превышать 2MB',
        ];
    }
}
