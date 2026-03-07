<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_response_id')->constrained('vacancy_responses')->onDelete('restrict');
            $table->foreignId('sender_id')->constrained('users')->onDelete('restrict');
            $table->string('message', 4096);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
