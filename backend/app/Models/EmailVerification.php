<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'last_sent_at',
        'expires_at',
        'send_attempts',
        'send_blocked_until'
    ];
}
