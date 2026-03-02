<?php

namespace App\Services\Auth;

use App\Models\EmailVerification;
use App\Models\User;

class EmailVerificationService
{
    public function sendEmailVerification(User $user)
    {
        $emailVerification = EmailVerification::firstOrNew(
            ['user_id', $user->id]
        );

        if ($emailVerification->sent_blocked_until && $emailVerification->sent_blocked_until > now()) {
            $minutes = now()->diffInMinutes($emailVerification->sent_blocked_until);
            return [
                'success' => false,
                'message' => "Отправка писем заблокирована на ${minutes} мин"
            ];
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $emailVerification->code = $code;
        $emailVerification->expires_at = now()->addMinutes(30);
        $emailVerification->send_attemtps = ($emailVerification->send_attempts ?? 0) + 1;

        if ($emailVerification->send_attemtps >= 3) {
            $emailVerification->send_blocked_until = now()->addMinutes(30);
            $emailVerification->send_attemtps = 0;
        }

        $emailVerification->save();

        SendVerificationEmailJob::dispatch($user, $code);

        return [
            'success' => true,
            'message' => 'Код отправлен'
        ];
    }
}
