<?php

namespace Database\Seeders;

use App\Models\WorkSchedule;
use Illuminate\Database\Seeder;

class WorkScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            ['name' => 'Полная занятость', 'sort' => 1],
            ['name' => 'Частичная занятость', 'sort' => 2],
            ['name' => 'Гибрид', 'sort' => 3],
            ['name' => 'Удаленная работа', 'sort' => 4],
            ['name' => 'Вахты', 'sort' => 5],
        ];

        foreach ($schedules as $schedule) {
            WorkSchedule::updateOrCreate(
                ['name' => $schedule['name']],
                ['sort' => $schedule['sort']]
            );
        }
    }
}
