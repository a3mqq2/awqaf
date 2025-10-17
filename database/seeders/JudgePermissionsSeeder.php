<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class JudgePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تأكد من وجود الدور
        $judgeRole = Role::firstOrCreate(['name' => 'judge']);

        // إنشاء الصلاحيات إن لم تكن موجودة
        $permissions = [
            'exam.scientific' => 'امتحان المنهج العلمي',
            'exam.oral' => 'امتحان الشفوي',
        ];

        foreach ($permissions as $name => $label) {
            Permission::firstOrCreate(
                ['name' => $name],
                ['guard_name' => 'web'] // تأكد من تطابق الـ guard مع المستخدمين
            );
        }

        // ربط الصلاحيات بالدور
        $judgeRole->givePermissionTo(array_keys($permissions));

        // طباعة تأكيد في الكونسول
        $this->command->info('✅ Permissions assigned to Judge role successfully.');
    }
}
