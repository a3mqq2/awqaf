<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class SystemLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'system_logs'],['name' => 'system_logs', 'guard_name' => 'web', 'name_ar' => 'سجلات النظام']);
        Permission::firstOrCreate(['name' => 'backup'],['name' => 'backup', 'guard_name' => 'web', 'name_ar' => ' النسخ الاحتياطي' ]);
    }
}
