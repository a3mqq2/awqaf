<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('examinees', function (Blueprint $table) {
            $table->boolean('is_attended')->default(false)->after('status'); // هل حضر؟
            $table->timestamp('attended_at')->nullable()->after('is_attended'); // وقت الحضور
            $table->foreignId('committee_id')->nullable()->constrained('committees')->nullOnDelete()->after('cluster_id'); // اللجنة
        });
    }

    public function down(): void
    {
        Schema::table('examinees', function (Blueprint $table) {
            $table->dropColumn(['is_attended', 'attended_at', 'committee_id']);
        });
    }
};