<?php
// database/seeders/EducationDegreeSeeder.php

namespace Database\Seeders;

use App\Models\EducationDegree;
use Illuminate\Database\Seeder;

class EducationDegreeSeeder extends Seeder
{
    public function run(): void
    {
        $degrees = ['Среднее', 'Среднее специальное', 'Неполное высшее', 'Высшее', 'Магистр', 'Кандидат наук'];

        foreach ($degrees as $index => $degree) {
            EducationDegree::create([
                'name' => $degree,
                'sort' => $index,
            ]);
        }
    }
}
