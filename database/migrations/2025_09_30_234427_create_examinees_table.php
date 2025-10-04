<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examinees', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('father_name')->nullable();
            $table->string('grandfather_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('nationality')->nullable();
            $table->string('national_id')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('current_residence')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birth_date')->nullable();

            $table->foreignId('office_id')->constrained('offices')->cascadeOnDelete();
            $table->foreignId('cluster_id')->constrained('clusters')->cascadeOnDelete();
            $table->enum('status', ['confirmed','pending','withdrawn','under_review'])->default('pending');
            $table->boolean('has_previous_exam')->default(false);
            $table->text('notes')->nullable();

            $table->foreignId('narration_id')->nullable()->constrained('narrations')->nullOnDelete();
            $table->foreignId('drawing_id')->nullable()->constrained('drawings')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examinees');
    }
};
