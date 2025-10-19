<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleanPermissionsSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🧹 بدء تنظيف الصلاحيات والأدوار...');

        // تنظيف الكاش
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1️⃣ حذف جميع العلاقات
        $this->command->info('🗑️  حذف علاقات المستخدمين...');
        DB::table('model_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('role_has_permissions')->delete();

        // 2️⃣ حذف جميع الصلاحيات والأدوار القديمة
        $this->command->info('🗑️  حذف الصلاحيات والأدوار القديمة...');
        DB::table('permissions')->delete();
        DB::table('roles')->delete();

        // 3️⃣ إعادة إنشاء جميع الصلاحيات
        $this->command->info('✨ إنشاء الصلاحيات الجديدة...');
        $this->createAllPermissions();

        // 4️⃣ إنشاء الأدوار مع صلاحياتها
        $this->command->info('✨ إنشاء الأدوار...');
        $this->createRoles();

        // 5️⃣ إعطاء المستخدم الأول دور Admin
        $this->command->info('👤 تعيين المستخدم الأول كـ Admin...');
        $this->assignAdminToFirstUser();

        // تنظيف الكاش مرة أخرى
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->command->info('✅ تم تنظيف وإعادة بناء نظام الصلاحيات بنجاح!');
        $this->command->info('📊 الإحصائيات:');
        $this->command->info('   - عدد الصلاحيات: ' . Permission::count());
        $this->command->info('   - عدد الأدوار: ' . Role::count());
    }

    /**
     * إنشاء جميع الصلاحيات
     */
    private function createAllPermissions()
    {
        $permissions = [
            // 📋 صلاحيات اللجان
            ['name' => 'committees.view', 'name_ar' => 'عرض اللجان'],
            ['name' => 'committees.create', 'name_ar' => 'إنشاء لجنة'],
            ['name' => 'committees.edit', 'name_ar' => 'تعديل اللجنة'],
            ['name' => 'committees.delete', 'name_ar' => 'حذف اللجنة'],
            
            // 👨‍⚖️ صلاحيات المحكمين
            ['name' => 'judges.view', 'name_ar' => 'عرض المحكمين'],
            ['name' => 'judges.create', 'name_ar' => 'إضافة محكم'],
            ['name' => 'judges.edit', 'name_ar' => 'تعديل محكم'],
            ['name' => 'judges.delete', 'name_ar' => 'حذف محكم'],
            
            // ✅ صلاحيات الحضور
            ['name' => 'attendance.mark', 'name_ar' => 'تسجيل الحضور'],
            ['name' => 'attendance.view', 'name_ar' => 'عرض سجل الحضور'],
            
            // 👥 صلاحيات الممتحنين
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
            ['name' => 'examinees.change-status', 'name_ar' => 'تغيير حالة الممتحن'],
            
            // 📊 صلاحيات التقارير
            ['name' => 'reports.examinees', 'name_ar' => 'نتائج الممتحنين'],
            
            // 📝 صلاحيات الامتحانات
            ['name' => 'exam.oral', 'name_ar' => 'الامتحان الشفوي'],
            ['name' => 'exam.scientific', 'name_ar' => 'امتحان المنهج العلمي'],
            
            // ⚙️ صلاحيات إدارية
            ['name' => 'users', 'name_ar' => 'إدارة المستخدمين'],
            ['name' => 'clusters', 'name_ar' => 'إدارة التجمعات'],
            ['name' => 'offices', 'name_ar' => 'إدارة المكاتب'],
            ['name' => 'narrations', 'name_ar' => 'إدارة الروايات'],
            ['name' => 'drawings', 'name_ar' => 'إدارة رسوم المصاحف'],
            ['name' => 'system_logs', 'name_ar' => 'سجلات النظام'],
            ['name' => 'backup', 'name_ar' => 'النسخ الاحتياطي'],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission['name'],
                'name_ar' => $permission['name_ar'],
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * إنشاء الأدوار مع صلاحياتها
     */
    private function createRoles()
    {
        // 1️⃣ مدير النظام - جميع الصلاحيات
        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        $adminRole->givePermissionTo(Permission::all());
        $this->command->info('   ✅ Admin: ' . $adminRole->permissions->count() . ' صلاحية');

        // 2️⃣ مشرف اللجنة
        $supervisorRole = Role::create([
            'name' => 'committee_supervisor',
            'guard_name' => 'web'
        ]);
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
            // الممتحنين
            'examinees.view',
            'examinees.view-details',
            'examinees.create',
            'examinees.edit',
            'examinees.approve',
            'examinees.reject',
            'examinees.print',
            'examinees.print-cards',
            'examinees.change-status',
            // الحضور
            'attendance.mark',
            'attendance.view',
            // التقارير
            'reports.examinees',
        ];
        $supervisorRole->givePermissionTo($supervisorPermissions);
        $this->command->info('   ✅ Committee Supervisor: ' . $supervisorRole->permissions->count() . ' صلاحية');

        // 3️⃣ كنترول اللجنة
        $controlRole = Role::create([
            'name' => 'committee_control',
            'guard_name' => 'web'
        ]);
        $controlPermissions = [
            'attendance.mark',
            'attendance.view',
            'examinees.view',
            'examinees.view-details',
        ];
        $controlRole->givePermissionTo($controlPermissions);
        $this->command->info('   ✅ Committee Control: ' . $controlRole->permissions->count() . ' صلاحية');

        // 4️⃣ المحكم
        $judgeRole = Role::create([
            'name' => 'judge',
            'guard_name' => 'web'
        ]);
        $judgePermissions = [
            'exam.oral',
            'exam.scientific',
            'examinees.view',
            'examinees.view-details',
        ];
        $judgeRole->givePermissionTo($judgePermissions);
        $this->command->info('   ✅ Judge: ' . $judgeRole->permissions->count() . ' صلاحية');
    }

    /**
     * تعيين المستخدم الأول كـ Admin
     */
    private function assignAdminToFirstUser()
    {
        $adminUser = User::first();
        
        if ($adminUser) {
            $adminUser->syncRoles(['admin']);
            $this->command->info('   ✅ تم تعيين ' . $adminUser->name . ' كمدير نظام');
        } else {
            $this->command->warn('   ⚠️  لا يوجد مستخدمين في النظام');
        }
    }
}