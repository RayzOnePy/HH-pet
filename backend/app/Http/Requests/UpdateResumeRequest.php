<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'salary' => 'nullable|integer|min:0',
            'is_active' => 'boolean',

            // График работы
            'work_schedule_ids' => 'nullable|array',
            'work_schedule_ids.*' => 'exists:work_schedules,id',

            // Контакты
            'contacts' => 'nullable|array',
            'contacts.*.id' => 'nullable|exists:resume_contacts,id',
            'contacts.*.type' => ['required', Rule::in(['phone', 'email', 'telegram', 'whatsapp'])],
            'contacts.*.value' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $index = explode('.', $attribute)[1];
                    $type = $this->input("contacts.{$index}.type");

                    if ($type === 'email') {
                        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $fail('Укажите корректный email адрес');
                        }
                    } elseif ($type === 'phone') {
                        $phonePattern = '/^[\+]?[0-9\s\-\(\)]{10,20}$/';
                        if (!preg_match($phonePattern, $value)) {
                            $fail('Укажите корректный номер телефона');
                        }
                        $digitsOnly = preg_replace('/[^0-9]/', '', $value);
                        if (strlen($digitsOnly) < 10 || strlen($digitsOnly) > 15) {
                            $fail('Номер телефона должен содержать от 10 до 15 цифр');
                        }
                    } elseif ($type === 'telegram') {
                        $telegramPattern = '/^@?[a-zA-Z0-9_]{5,32}$/';
                        if (!preg_match($telegramPattern, $value)) {
                            $fail('Telegram username должен содержать от 5 до 32 символов (латиница, цифры, _)');
                        }
                    } elseif ($type === 'whatsapp') {
                        $whatsappPattern = '/^[\+]?[0-9\s\-\(\)]{10,20}$/';
                        if (!preg_match($whatsappPattern, $value)) {
                            $fail('Укажите корректный номер WhatsApp');
                        }
                        $digitsOnly = preg_replace('/[^0-9]/', '', $value);
                        if (strlen($digitsOnly) < 10 || strlen($digitsOnly) > 15) {
                            $fail('Номер WhatsApp должен содержать от 10 до 15 цифр');
                        }
                    }
                },
            ],

            // Навыки
            'skills' => 'nullable|array',
            'skills.*.id' => 'nullable|exists:resume_skills,id',
            'skills.*.skill' => 'required|string|max:100',
            'skills.*.level' => ['required', Rule::in(['beginner', 'intermediate', 'advanced'])],

            // Опыт работы
            'work_experiences' => 'nullable|array',
            'work_experiences.*.id' => 'nullable|exists:resume_works,id',
            'work_experiences.*.title' => 'required|string|max:255',
            'work_experiences.*.experience_summary' => 'required|string',
            'work_experiences.*.start_date' => 'required|date',
            'work_experiences.*.end_date' => 'nullable|date|after:work_experiences.*.start_date',
            'work_experiences.*.is_current' => 'boolean',

            // Образование
            'educations' => 'nullable|array',
            'educations.*.id' => 'nullable|exists:resume_educations,id',
            'educations.*.institution' => 'required|string|max:255',
            'educations.*.faculty' => 'required|string|max:255',
            'educations.*.specialty' => 'required|string|max:255',
            'educations.*.qualification' => 'required|string|max:255',
            'educations.*.degree_id' => 'required|exists:education_degrees,id',
            'educations.*.start_date' => 'required|date',
            'educations.*.end_date' => 'nullable|date|after:educations.*.start_date',
            'educations.*.is_current' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.string' => 'Название резюме должно быть строкой',
            'title.max' => 'Название резюме не должно превышать 255 символов',

            'salary.integer' => 'Зарплата должна быть числом',
            'salary.min' => 'Зарплата не может быть отрицательной',
            'is_active.boolean' => 'Поле "активность" должно быть true или false',

            // График работы
            'work_schedule_ids.array' => 'График работы должен быть передан массивом',
            'work_schedule_ids.*.exists' => 'Выбранный график работы не существует',

            // Контакты
            'contacts.array' => 'Контакты должны быть переданы массивом',
            'contacts.*.id.exists' => 'Контакт не найден',
            'contacts.*.type.required' => 'Укажите тип контакта',
            'contacts.*.type.in' => 'Тип контакта может быть: phone, email, telegram, whatsapp',
            'contacts.*.value.required' => 'Укажите значение контакта',
            'contacts.*.value.string' => 'Значение контакта должно быть строкой',
            'contacts.*.value.max' => 'Значение контакта не должно превышать 255 символов',

            // Навыки
            'skills.array' => 'Навыки должны быть переданы массивом',
            'skills.*.id.exists' => 'Навык не найден',
            'skills.*.skill.required' => 'Укажите название навыка',
            'skills.*.skill.string' => 'Название навыка должно быть строкой',
            'skills.*.skill.max' => 'Название навыка не должно превышать 100 символов',
            'skills.*.level.required' => 'Укажите уровень владения навыком',
            'skills.*.level.in' => 'Уровень навыка может быть: beginner, intermediate, advanced',

            // Опыт работы
            'work_experiences.array' => 'Опыт работы должен быть передан массивом',
            'work_experiences.*.id.exists' => 'Запись об опыте работы не найдена',
            'work_experiences.*.title.required' => 'Укажите должность',
            'work_experiences.*.title.string' => 'Должность должна быть строкой',
            'work_experiences.*.title.max' => 'Должность не должна превышать 255 символов',
            'work_experiences.*.experience_summary.required' => 'Опишите ваши обязанности и достижения',
            'work_experiences.*.experience_summary.string' => 'Описание опыта работы должно быть строкой',
            'work_experiences.*.start_date.required' => 'Укажите дату начала работы',
            'work_experiences.*.start_date.date' => 'Дата начала работы должна быть корректной датой',
            'work_experiences.*.end_date.date' => 'Дата окончания работы должна быть корректной датой',
            'work_experiences.*.end_date.after' => 'Дата окончания работы должна быть позже даты начала',
            'work_experiences.*.is_current.boolean' => 'Поле "текущая работа" должно быть true или false',

            // Образование
            'educations.array' => 'Образование должно быть передано массивом',
            'educations.*.id.exists' => 'Запись об образовании не найдена',
            'educations.*.institution.required' => 'Укажите учебное заведение',
            'educations.*.institution.string' => 'Название учебного заведения должно быть строкой',
            'educations.*.institution.max' => 'Название учебного заведения не должно превышать 255 символов',
            'educations.*.faculty.required' => 'Укажите факультет',
            'educations.*.faculty.string' => 'Название факультета должно быть строкой',
            'educations.*.faculty.max' => 'Название факультета не должно превышать 255 символов',
            'educations.*.specialty.required' => 'Укажите специальность',
            'educations.*.specialty.string' => 'Название специальности должно быть строкой',
            'educations.*.specialty.max' => 'Название специальности не должно превышать 255 символов',
            'educations.*.qualification.required' => 'Укажите квалификацию',
            'educations.*.qualification.string' => 'Квалификация должна быть строкой',
            'educations.*.qualification.max' => 'Квалификация не должна превышать 255 символов',
            'educations.*.degree_id.required' => 'Выберите степень образования',
            'educations.*.degree_id.exists' => 'Выбранная степень образования не существует',
            'educations.*.start_date.required' => 'Укажите дату начала обучения',
            'educations.*.start_date.date' => 'Дата начала обучения должна быть корректной датой',
            'educations.*.end_date.date' => 'Дата окончания обучения должна быть корректной датой',
            'educations.*.end_date.after' => 'Дата окончания обучения должна быть позже даты начала',
            'educations.*.is_current.boolean' => 'Поле "текущее обучение" должно быть true или false',
        ];
    }
}
