<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\ResponsesController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\VacancyController;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API работает!',
    ]);
});

// ==================== ПУБЛИЧНЫЕ МАРШРУТЫ ====================

// Справочники
Route::prefix('dictionaries')->group(function () {
    Route::get('/education-degrees', [DictionaryController::class, 'educationDegrees']);
    Route::get('/employment-types', [DictionaryController::class, 'employmentTypes']);
    Route::get('/work-schedules', [DictionaryController::class, 'workSchedules']);
});

// Авторизация
Route::prefix('auth')->group(function () {
    Route::post('/send-verification', [AuthController::class, 'sendVerification']);
    Route::post('/check-verification-code', [AuthController::class, 'checkVerificationCode']);
    Route::post('/create-user', [AuthController::class, 'createUser']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Публичные маршруты вакансий
Route::middleware('auth.optional')->group(function () {
    Route::get('/vacancies', [VacancyController::class, 'index']);
    Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show']);
});

// Публичные маршруты резюме
Route::get('/resumes', [ResumeController::class, 'index']);
Route::get('/resumes/{resume}', [ResumeController::class, 'show']);

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

        // Отклики
        Route::get('/responses', [ResponsesController::class, 'employerResponses']);
        Route::get('/responses/statistics', [ResponsesController::class, 'statistics']);
        Route::patch('/responses/{response}/status', [ResponsesController::class, 'updateStatus']);
    });

    // ===== МАРШРУТЫ ДЛЯ СОИСКАТЕЛЯ =====
    Route::prefix('applicant')->group(function () {

        // Резюме
        Route::get('/resume', [ResumeController::class, 'myResume']);
        Route::post('/resume', [ResumeController::class, 'store']);
        Route::put('/resume', [ResumeController::class, 'update']);
        Route::patch('/resume/toggle-active', [ResumeController::class, 'toggleActive']);

        // Избранные вакансии
        Route::get('/favorites', [VacancyController::class, 'favorites']);
        Route::post('/favorites/{vacancy}', [VacancyController::class, 'addToFavorites']);
        Route::delete('/favorites/{vacancy}', [VacancyController::class, 'removeFromFavorites']);

        // Отклики
        Route::get('/responses', [ResponsesController::class, 'myResponses']);
        Route::post('/responses/{vacancy}', [ResponsesController::class, 'respond']);
        Route::delete('/responses/{response}', [ResponsesController::class, 'cancelResponse']);
        Route::get('/responses/counts', [ResponsesController::class, 'counts']);
    });
});
