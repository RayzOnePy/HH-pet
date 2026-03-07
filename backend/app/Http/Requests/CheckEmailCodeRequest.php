<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckEmailCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'code' => ['required', 'max:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Этот email уже зарегистрирован',
        ];
    }
}
