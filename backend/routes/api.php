<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VacancyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API работает!',
    ]);
});

// Авторизация
Route::prefix('auth')->group(function () {
    Route::post('/send-verification', [AuthController::class, 'sendVerification']);
    Route::post('/check-verification-code', [AuthController::class, 'checkVerificationCode']);
    Route::post('/create-user', [AuthController::class, 'createUser']);

    Route::post('/login', [AuthController::class, 'login']);
});

// Публичные маршруты вакансий
Route::get('vacancies', [VacancyController::class, 'index']);
Route::get('vacancies/{vacancy}', [VacancyController::class, 'show']);

// Публичные маршруты компаний
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{company}', [CompanyController::class, 'show']);

// Защищенные маршруты
Route::middleware('auth:sanctum')->group(function () {
    // Авторизация
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // Вакансии
    Route::get('my-vacancies', [VacancyController::class, 'myVacancies']);
    Route::post('vacancies', [VacancyController::class, 'store']);
    Route::put('vacancies/{vacancy}', [VacancyController::class, 'update']);
    Route::delete('vacancies/{vacancy}', [VacancyController::class, 'destroy']);
    Route::patch('vacancies/{vacancy}', [VacancyController::class, 'toggleStatus']);
    Route::patch('vacancies/{vacancy}/restore', [VacancyController::class, 'restore']);

    // Компании
    Route::get('/my-company', [CompanyController::class, 'myCompany']);
    Route::post('/companies', [CompanyController::class, 'store']);
    Route::put('/companies/{company}', [CompanyController::class, 'update']);
});
