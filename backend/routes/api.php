<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'message' => 'API работает!',
    ]);
});

Route::prefix('auth')->group(function () {
    Route::post('/send-verification', [AuthController::class, 'sendVerification']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerification']);

    Route::post('/check-verification-code', [AuthController::class, 'checkVerificationCode']);
    Route::post('/create-user', [AuthController::class, 'createUser']);

    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('verified')->group(function () {
       // ToDo
    });
});
