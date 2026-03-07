<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendVerificationEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(string $email, string $code)
    {
        //
    }

    public function handle(): void
    {
        //
    }
}
