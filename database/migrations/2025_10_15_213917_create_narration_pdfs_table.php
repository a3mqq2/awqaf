<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('narration_pdfs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('narration_id')->constrained()->onDelete('cascade');
            $table->string('title'); // عنوان الملف
            $table->string('file_path'); // مسار الملف
            $table->integer('order')->default(0); // الترتيب
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('narration_pdfs');
    }
};