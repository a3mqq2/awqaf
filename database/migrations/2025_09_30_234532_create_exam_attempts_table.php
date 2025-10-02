<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examinee_id')->constrained('examinees')->cascadeOnDelete();
            $table->string('year')->nullable();
            $table->foreignId('narration_id')->nullable()->constrained('narrations')->nullOnDelete();
            $table->foreignId('drawing_id')->nullable()->constrained('drawings')->nullOnDelete();
            $table->string('side')->nullable();
            $table->string('result')->nullable();
            $table->string('percentage')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};