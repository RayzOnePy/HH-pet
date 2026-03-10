<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'code',
        'email',
        'sent_at',
        'expires_at',
        'user_data'
    ];
}
