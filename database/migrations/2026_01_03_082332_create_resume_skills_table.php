<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resume_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->unique()->constrained()->onDelete('cascade');
            $table->string('skill');
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->timestamps();

            $table->unique(['resume_id', 'skill']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_skills');
    }
};
