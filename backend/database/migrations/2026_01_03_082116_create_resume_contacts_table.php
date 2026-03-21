<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resume_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resume_id')->constrained('resumes')->onDelete('cascade');
            $table->enum('type', ['phone', 'email', 'telegram', 'whatsapp']);
            $table->string('value')->nullable(false);
            $table->timestamps();

            $table->unique(['resume_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_contacts');
    }
};
