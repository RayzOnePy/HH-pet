<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resume_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained('resumes')->onDelete('cascade');
            $table->string('institution')->nullable(false);
            $table->string('faculty')->nullable(false);
            $table->string('specialty')->nullable(false);
            $table->string('qualification')->nullable(false);
            $table->foreignId('degree_id')->constrained('education_degrees')->onDelete('restrict');
            $table->date('start_date')->nullable(false);
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_educations');
    }
};
