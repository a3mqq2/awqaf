<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // لـ MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE examinees MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
        } 
        // لـ PostgreSQL
        elseif (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE examinees ALTER COLUMN status TYPE VARCHAR(50)");
            DB::statement("ALTER TABLE examinees ALTER COLUMN status SET DEFAULT 'pending'");
        }
        // لـ SQLite
        else {
            Schema::table('examinees', function (Blueprint $table) {
                $table->string('status', 50)->default('pending')->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا نحتاج reverse لأن string أفضل من enum
        // لكن إذا أردت العودة لـ enum:
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE examinees MODIFY COLUMN status ENUM('pending', 'confirmed', 'attended', 'withdrawn', 'rejected') DEFAULT 'pending'");
        }
    }
};