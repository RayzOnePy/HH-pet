<?php

namespace App\Http\Controllers;

use App\Models\EducationDegree;
use App\Models\WorkSchedule;
use Illuminate\Http\JsonResponse;

class DictionaryController extends Controller
{
    public function educationDegrees(): JsonResponse
    {
        $degrees = EducationDegree::orderBy('sort')->get();

        return response()->json([
            'data' => $degrees->map(fn($degree) => [
                'id' => $degree->id,
                'name' => $degree->name,
                'sort' => $degree->sort,
            ])
        ]);
    }

    public function workSchedules(): JsonResponse
    {
        $schedules = WorkSchedule::orderBy('id')->get();

        return response()->json([
            'data' => $schedules->map(fn($schedule) => [
                'id' => $schedule->id,
                'name' => $schedule->name,
            ])
        ]);
    }
}
