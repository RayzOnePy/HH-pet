<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case APPLICANT = 'applicant';
    case EMPLOYER = 'employer';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Администратор',
            self::APPLICANT => 'Соискатель',
            self::EMPLOYER => 'Работодатель',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function forRegistration(): array
    {
        return [
          self::APPLICANT->value,
          self::EMPLOYER->value,
        ];
    }
}
