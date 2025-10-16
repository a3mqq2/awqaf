<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('oral_evaluations', function (Blueprint $table) {
            // إزالة الأعمدة القديمة
            $table->dropColumn([
                'repetition',
                'hesitation',
                'restart',
                'minor_mistake',
                'major_mistake',
                'performance',
                'bad_stop',
                'incomplete_stop'
            ]);
        });

        Schema::table('oral_evaluations', function (Blueprint $table) {
            // إضافة حقل JSON لتخزين تقييمات الأسئلة الـ 12
            $table->json('questions_data')->nullable()->after('committee_id')->comment('بيانات الأسئلة الـ 12');
            $table->integer('current_question')->default(1)->after('questions_data')->comment('رقم السؤال الحالي');
            $table->decimal('total_score', 5, 2)->default(0)->after('current_question')->comment('مجموع الدرجات');
        });
    }

    public function down(): void
    {
        Schema::table('oral_evaluations', function (Blueprint $table) {
            $table->dropColumn(['questions_data', 'current_question', 'total_score']);
            
            // إعادة الأعمدة القديمة
            $table->integer('repetition')->default(0);
            $table->integer('hesitation')->default(0);
            $table->integer('restart')->default(0);
            $table->integer('minor_mistake')->default(0);
            $table->integer('major_mistake')->default(0);
            $table->integer('performance')->default(0);
            $table->integer('bad_stop')->default(0);
            $table->integer('incomplete_stop')->default(0);
        });
    }
};