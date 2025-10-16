<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oral_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examinee_id')->constrained()->onDelete('cascade');
            $table->foreignId('judge_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('committee_id')->constrained()->onDelete('cascade');
            
            // بنود الحفظ
            $table->integer('repetition')->default(0)->comment('تكرار');
            $table->integer('hesitation')->default(0)->comment('تردد');
            $table->integer('restart')->default(0)->comment('إعادة');
            
            // اللغة
            $table->integer('minor_mistake')->default(0)->comment('لحن خفي');
            $table->integer('major_mistake')->default(0)->comment('لحن جلي');
            
            // الصوت والأداء
            $table->integer('performance')->default(0)->comment('الأداء');
            
            // الوقف
            $table->integer('bad_stop')->default(0)->comment('وقف قبيح');
            $table->integer('incomplete_stop')->default(0)->comment('وقف لا يتم به المعنى');
            
            // النتيجة النهائية
            $table->decimal('final_score', 5, 2)->nullable()->comment('الدرجة النهائية');
            $table->text('notes')->nullable()->comment('ملاحظات المحكم');
            
            $table->enum('status', ['pending', 'in_progress', 'completed', 'excluded'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // لا يمكن للمحكم تقييم نفس الممتحن مرتين في الاختبار الشفهي
            $table->unique(['examinee_id', 'judge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oral_evaluations');
    }
};