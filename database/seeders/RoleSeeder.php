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
        // إنشاء جميع الصلاحيات أولاً
        $this->createAllPermissions();

        // 1️⃣ مدير النظام - جميع الصلاحيات
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['guard_name' => 'web']
        );
        $allPermissions = Permission::all();
        $adminRole->syncPermissions($allPermissions);

        // 2️⃣ مشرف اللجنة
        $supervisorRole = Role::firstOrCreate(
            ['name' => 'committee_supervisor'],
            ['guard_name' => 'web']
        );
        $supervisorPermissions = [
            'committees.view',
            'committees.create',
            'committees.edit',
            'committees.delete',
            'judges.view',
            'judges.create',
            'judges.edit',
            'judges.delete',
            'examinees.view',
            'examinees.view-details',
            'attendance.mark',
            'attendance.view',
        ];
        $supervisorRole->syncPermissions($supervisorPermissions);

        // 3️⃣ كنترول اللجنة
        $controlRole = Role::firstOrCreate(
            ['name' => 'committee_control'],
            ['guard_name' => 'web']
        );
        $controlPermissions = [
            'attendance.mark',
            'attendance.view',
            'examinees.view',
            'examinees.view-details',
        ];
        $controlRole->syncPermissions($controlPermissions);

        // 4️⃣ المحكم
        $judgeRole = Role::firstOrCreate(
            ['name' => 'judge'],
            ['guard_name' => 'web']
        );
        $judgePermissions = [
            'exam.oral',
            'exam.scientific',
        ];
        $judgeRole->syncPermissions($judgePermissions);

        // إعطاء Role Admin للمستخدم الأول
        $adminUser = User::first();
        if ($adminUser) {
            $adminUser->syncRoles(['admin']);
        }

        $this->command->info('✅ تم إنشاء الأدوار والصلاحيات بنجاح!');
    }

    private function createAllPermissions()
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
            
            // صلاحيات الممتحنين
            ['name' => 'examinees.view', 'name_ar' => 'عرض الممتحنين'],
            ['name' => 'examinees.view-details', 'name_ar' => 'عرض تفاصيل الممتحن'],
            ['name' => 'examinees.create', 'name_ar' => 'إضافة ممتحن'],
            ['name' => 'examinees.edit', 'name_ar' => 'تعديل ممتحن'],
            ['name' => 'examinees.delete', 'name_ar' => 'حذف ممتحن'],
            ['name' => 'examinees.approve', 'name_ar' => 'قبول الممتحنين'],
            ['name' => 'examinees.reject', 'name_ar' => 'رفض الممتحنين'],
            ['name' => 'examinees.print', 'name_ar' => 'طباعة قائمة الممتحنين'],
            ['name' => 'examinees.print-cards', 'name_ar' => 'طباعة بطاقات الممتحنين'],
            ['name' => 'examinees.import', 'name_ar' => 'استيراد الممتحنين'],
            
            // صلاحيات الامتحانات
            ['name' => 'exam.oral', 'name_ar' => 'الامتحان الشفوي'],
            ['name' => 'exam.scientific', 'name_ar' => 'الامتحان  (المنهج العلمي)'],
            
            // صلاحيات إدارية
            ['name' => 'users', 'name_ar' => 'إدارة المستخدمين'],
            ['name' => 'clusters', 'name_ar' => 'إدارة التجمعات'],
            ['name' => 'offices', 'name_ar' => 'إدارة المكاتب'],
            ['name' => 'narrations', 'name_ar' => 'إدارة الروايات'],
            ['name' => 'drawings', 'name_ar' => 'إدارة رسوم المصاحف'],
            ['name' => 'system_logs', 'name_ar' => 'سجلات النظام'],
            ['name' => 'backup', 'name_ar' => 'النسخ الاحتياطي'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['name_ar' => $permission['name_ar'], 'guard_name' => 'web']
            );
        }
    }
}