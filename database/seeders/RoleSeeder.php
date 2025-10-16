<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // ✅ إنشاء صلاحيات اللجان أولاً
        $this->createCommitteePermissions();

        // 1️⃣ إنشاء Role الأدمن
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );

        // إعطاء جميع الصلاحيات الموجودة لـ Admin
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);

        // إعطاء Role Admin للمستخدم الأول
        $adminUser = User::first();
        if ($adminUser) {
            $adminUser->syncRoles(['admin']);
        }

        // 2️⃣ إنشاء Role مشرف اللجان
        $supervisorRole = Role::firstOrCreate(
            ['name' => 'committee_supervisor'],
            ['guard_name' => 'web']
        );

        // صلاحيات مشرف اللجان
        $supervisorPermissions = [
            // إدارة اللجان
            'committees.view',
            'committees.create',
            'committees.edit',
            'committees.delete',
            
            // إدارة المحكمين
            'judges.view',
            'judges.create',
            'judges.edit',
            'judges.delete',
            
            // عرض الممتحنين
            'examinees.view',
            'examinees.view-details',
            
            // تسجيل الحضور
            'attendance.mark',
            'attendance.view',
        ];

        $supervisorRole->syncPermissions($supervisorPermissions);

        // 3️⃣ إنشاء Role المحكم
        $judgeRole = Role::firstOrCreate(
            ['name' => 'judge'],
            ['guard_name' => 'web']
        );

        // صلاحيات المحكم
        $judgePermissions = [
            'examinees.view',
            'examinees.view-details',
        ];

        $judgeRole->syncPermissions($judgePermissions);

        // 4️⃣ إنشاء Role كنترول اللجنة
        $controlRole = Role::firstOrCreate(
            ['name' => 'committee_control'],
            ['guard_name' => 'web']
        );

        // صلاحيات كنترول اللجنة
        $controlPermissions = [
            'examinees.view',
            'examinees.view-details',
            'attendance.mark',
            'attendance.view',
        ];

        $controlRole->syncPermissions($controlPermissions);

        $this->command->info('✅ تم إنشاء الأدوار والصلاحيات بنجاح!');
    }

    /**
     * إنشاء صلاحيات اللجان
     */
    private function createCommitteePermissions()
    {
        $permissions = [
            // صلاحيات اللجان
            ['name' => 'committees.view', 'name_ar' => 'عرض اللجان'],
            ['name' => 'committees.create', 'name_ar' => 'إنشاء لجنة'],
            ['name' => 'committees.edit', 'name_ar' => 'تعديل اللجنة'],
            ['name' => 'committees.delete', 'name_ar' => 'حذف اللجنة'],
            
            // صلاحيات المحكمين
            ['name' => 'judges.view', 'name_ar' => 'عرض المحكمين'],
            ['name' => 'judges.create', 'name_ar' => 'إضافة محكم'],
            ['name' => 'judges.edit', 'name_ar' => 'تعديل محكم'],
            ['name' => 'judges.delete', 'name_ar' => 'حذف محكم'],
            
            // صلاحيات الحضور
            ['name' => 'attendance.mark', 'name_ar' => 'تسجيل الحضور'],
            ['name' => 'attendance.view', 'name_ar' => 'عرض سجل الحضور'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['name_ar' => $permission['name_ar'], 'guard_name' => 'web']
            );
        }

        $this->command->info('✅ تم إنشاء صلاحيات اللجان بنجاح!');
    }
}