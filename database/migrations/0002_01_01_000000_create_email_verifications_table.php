<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('code', 6);
            $table->timestamp('last_sent_at');
            $table->timestamp('expires_at');
            $table->timestamp('send_blocked_until');
            $table->smallInteger('send_attempts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
