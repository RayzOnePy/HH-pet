<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancy_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained('vacancies')->onDelete('restrict');
            $table->foreignId('candidate_id')->constrained('users')->onDelete('restrict');
            $table->enum('status', ['pending', 'invited', 'rejected', 'accepted'])->default('pending');
            $table->timestamps();

            $table->unique(['vacancy_id', 'candidate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancy_responses');
    }
};
