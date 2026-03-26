<?php

namespace App\Http\Requests;

use App\Enums\UserRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendVerificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Zа-яА-ЯёЁ\-]+$/u' // только буквы и дефис
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Zа-яА-ЯёЁ\-]+$/u'
            ],
            'middle_name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Zа-яА-ЯёЁ\-]*$/u'
            ],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'role' => ['required', Rule::in(UserRole::forRegistration())],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Имя обязательно для заполнения',
            'last_name.required' => 'Фамилия обязательна для заполнения',
            'email.required' => 'Email обязателен для заполнения',
            'role.required' => 'Роль обязательна для выбора',

            'first_name.regex' => 'Имя может содержать только буквы и дефис',
            'last_name.regex' => 'Фамилия может содержать только буквы и дефис',
            'middle_name.regex' => 'Отчество может содержать только буквы и дефис',

            'first_name.max' => 'Имя не должно превышать 255 символов',
            'last_name.max' => 'Фамилия не должна превышать 255 символов',
            'middle_name.max' => 'Отчество не должно превышать 255 символов',

            'email.email' => 'Введите корректный email адрес',
            'email.unique' => 'Этот email уже зарегистрирован',

            'role.in' => 'Выбрана недопустимая роль',
        ];
    }
}
