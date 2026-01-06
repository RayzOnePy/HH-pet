<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancy_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained()->onDelete('cascade');
            $table->foreignId('work_schedule_id')->constrained()->onDelete('restrict');
            $table->timestamps();

            $table->unique(['vacancy_id', 'work_schedule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancy_work_schedules');
    }
};
