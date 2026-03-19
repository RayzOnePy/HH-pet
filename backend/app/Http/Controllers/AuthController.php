<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEmailCodeRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\SendVerificationRequest;
use App\Jobs\SendVerificationEmailJob;
use App\Mail\RegistrationMail;
use App\Models\EmailVerification;
use App\Models\User;
use App\Services\Auth\EmailVerificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{
    public function sendVerification(SendVerificationRequest $request): JsonResponse
    {
        try {
            $existingVerification = EmailVerification::query()
                ->where('email', $request->email)
                ->where('expires_at', '>', now())
                ->first();

            if ($existingVerification) {
                return response()->json([
                    'message' => 'Код подтверждения уже был отправлен на почту',
                ], 429);
            }

            EmailVerification::query()
                ->where('email', $request->email)
                ->where('expires_at', '>', now())
                ->update(['expires_at' => now()]);

            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $verification = EmailVerification::create([
                'code' => $code,
                'email' => $request->email,
                'sent_at' => now(),
                'expires_at' => now()->addMinutes(30),
                'user_data' => json_encode([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'middle_name' => $request->middle_name,
                    'role' => $request->role,
                ])
            ]);

            SendVerificationEmailJob::dispatch($request->email, $code);

            return response()->json([
                'message' => 'Код подтверждения отправлен на email',
                'data' => [
                    'email' => $verification->email
                ]
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Не удалось отправить код подтверждения. Попробуйте позже.',
            ], 500);
        }
    }

    public function checkVerificationCode(CheckEmailCodeRequest $request): JsonResponse
    {
        $verification = EmailVerification::query()
            ->where('code', $request->code)
            ->where('email', $request->email)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Неверный код подтверждения',
            ], 400);
        }

        return response()->json([
            'message' => 'Код успешно подтвержден',
        ]);
    }

    public function createUser(RegisterUserRequest $request): JsonResponse
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'message' => 'Пользователь с таким email уже существует'
            ], 409);
        }
        $verification = EmailVerification::where('email', $request->email)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Код подтверждения недействителен или истёк'
            ], 400);
        }

        $userData = json_decode($verification->user_data);

        DB::beginTransaction();

        try {
            $user = User::create([
                'first_name' => $userData->first_name,
                'last_name' => $userData->last_name,
                'middle_name' => $userData->middle_name,
                'email' => $verification->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($userData->role);

            $token = $user->createToken('auth_token')->plainTextToken;

            $verification->delete();

            DB::commit();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            DB::rollBack();

            return response()->json([
                'message' => 'Ошибка при создании пользователя. попробуйте позже',
            ], 500);
        }

        return response()->json([
            'message' => 'Пользователь успешно создан',
            'data' => [
                'user' => $user,
                'token' => $token
            ]
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only('email', 'password');
            $remember = $request->boolean('remember', false);

            if (!Auth::attempt($credentials, $remember)) {
                return response()->json([
                    'message' => 'Неверный логин или пароль'
                ], 401);
            }

            $user = Auth::user();

            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;
            $user->load('roles');

            return response()->json([
                'message' => 'Успешный вход',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return response()->json([
                'message' => 'Ошибка при входе. попробуйте позже'
            ], 500);
        }
    }

    public function logout(): JsonResponse
    {
        Auth::user()->tokens()->delete();

        return response()->json([
            'message' => 'Успешный выход из системы'
        ]);
    }

    public function me(): JsonResponse
    {
        $user = Auth::user();
        $user->load('roles');

        return response()->json([
            'data' => [
                'user' => $user
            ]
        ]);
    }
}
