<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API работает!',
    ]);
});

// ==================== ПУБЛИЧНЫЕ МАРШРУТЫ ====================
Route::prefix('auth')->group(function () {
    Route::post('/send-verification', [AuthController::class, 'sendVerification']);
    Route::post('/check-verification-code', [AuthController::class, 'checkVerificationCode']);
    Route::post('/create-user', [AuthController::class, 'createUser']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Публичные маршруты вакансий
Route::get('/vacancies', [VacancyController::class, 'index']);
Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show']);

// Публичные маршруты компаний
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{company}', [CompanyController::class, 'show']);

// ==================== ЗАЩИЩЕННЫЕ МАРШРУТЫ ====================
Route::middleware('auth:sanctum')->group(function () {

    // Авторизация
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
    });

    // ===== МАРШРУТЫ ДЛЯ РАБОТОДАТЕЛЯ =====
    Route::prefix('employer')->group(function () {

        // Компания
        Route::get('/company', [CompanyController::class, 'myCompany']);
        Route::post('/company', [CompanyController::class, 'store']);
        Route::put('/company', [CompanyController::class, 'update']);

        // Вакансии
        Route::get('/vacancies', [VacancyController::class, 'myVacancies']);
        Route::post('/vacancies', [VacancyController::class, 'store']);
        Route::put('/vacancies/{vacancy}', [VacancyController::class, 'update']);
        Route::delete('/vacancies/{vacancy}', [VacancyController::class, 'destroy']);
        Route::patch('/vacancies/{vacancy}/toggle-status', [VacancyController::class, 'toggleStatus']);
        Route::patch('/vacancies/{vacancy}/restore', [VacancyController::class, 'restore']);
    });
});
