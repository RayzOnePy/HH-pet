<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Убедимся, что справочники заполнены
        $this->call([
            RoleSeeder::class,
            CompanyRoleSeeder::class,
            EducationDegreeSeeder::class,
            TestUserSeeder::class
        ]);
    }
}
