<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        $applicant = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'ocus288.applicant@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $applicant->assignRole(UserRole::APPLICANT);

        $employer = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'ocus288.employer@gmail.com',
            'password' => Hash::make('password'),
        ]);

        $employer->assignRole(UserRole::EMPLOYER);
    }
}
