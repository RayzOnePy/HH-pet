<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckEmailCodeRequest;
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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function sendVerification(SendVerificationRequest $request): JsonResponse
    {
        try {
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'message' => 'Пользователь с таким email уже существует',
                    'data' => []
                ]);
            }

            $existingVerification = EmailVerification::query()
                ->where('email', $request->email)
                ->where('expires_at', '>', now())
                ->first();

            if ($existingVerification) {
                return response()->json([
                    'message' => 'Код подтверждения уже был отправлен на почту',
                    'data' => []
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
                'data' => []
            ], 500);
        }
    }

    public function checkEmailCode(CheckEmailCodeRequest $request): JsonResponse
    {
        $verification = EmailVerification::query()
            ->where('code', $request->code)
            ->where('email', $request->email)
            ->where('expires_at', '>', now())
            ->first();

        if (!$verification) {
            return response()->json([
                'message' => 'Неверный код подтверждения',
                'data' => []
            ], 400);
        }

        return response()->json([
            'message' => 'Код успешно подтвержден',
            'data' => []
        ]);
    }

    public function createUser(RegisterUserRequest $request): JsonResponse
    {
        $verification = EmailVerification::firstWhere('code', $request->code);

        $user = User::create([
            'first_name' => $verification->user_data->first_name,
            'last_name' => $verification->user_data->last_name,
            'middle_name' => $verification->user_data->middle_name,
            'email' => $verification->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($verification->user_data->role);

        return response()->json();
    }
}
