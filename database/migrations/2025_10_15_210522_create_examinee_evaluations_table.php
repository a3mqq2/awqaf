<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examinee_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examinee_id')->constrained()->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade'); // المحكم
            $table->foreignId('committee_id')->constrained()->onDelete('cascade');
            $table->decimal('score', 5, 2)->nullable(); // الدرجة
            $table->text('notes')->nullable(); // ملاحظات المحكم
            $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
            $table->timestamp('started_at')->nullable(); // وقت بدء التقييم
            $table->timestamp('completed_at')->nullable(); // وقت انتهاء التقييم
            $table->timestamps();
            
            // لا يمكن للمحكم تقييم نفس الممتحن مرتين
            $table->unique(['examinee_id', 'judge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examinee_evaluations');
    }
};