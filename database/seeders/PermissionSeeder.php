<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // الصلاحيات الرئيسية للنظام
        $mainPermissions = [
            ['name' => 'users', 'name_ar' => 'إدارة المستخدمين'],
            ['name' => 'clusters', 'name_ar' => 'إدارة التجمعات'],
            ['name' => 'offices', 'name_ar' => 'إدارة المكاتب'],
            ['name' => 'narrations', 'name_ar' => 'إدارة الروايات'],
            ['name' => 'drawings', 'name_ar' => 'إدارة رسوم المصاحف'],
        ];

        foreach ($mainPermissions as $item) {
            Permission::firstOrCreate(
                ['name' => $item['name']],
                ['name_ar' => $item['name_ar'], 'guard_name' => 'web']
            );
        }

        // صلاحيات شؤون الممتحنين - مفصلة
        $examineePermissions = [
            // الصلاحيات الأساسية
            ['name' => 'examinees.view', 'name_ar' => 'عرض الممتحنين'],
            ['name' => 'examinees.create', 'name_ar' => 'إضافة ممتحن جديد'],
            ['name' => 'examinees.edit', 'name_ar' => 'تعديل بيانات الممتحنين'],
            ['name' => 'examinees.delete', 'name_ar' => 'حذف الممتحنين'],
            
            // صلاحيات القبول والرفض
            ['name' => 'examinees.approve', 'name_ar' => 'قبول الممتحنين'],
            ['name' => 'examinees.reject', 'name_ar' => 'رفض الممتحنين'],
            
            // صلاحيات الطباعة
            ['name' => 'examinees.print', 'name_ar' => 'طباعة قائمة الممتحنين'],
            ['name' => 'examinees.print-cards', 'name_ar' => 'طباعة بطاقات الممتحنين'],
            
            // صلاحيات الاستيراد والتصدير
            ['name' => 'examinees.import', 'name_ar' => 'استيراد الممتحنين من Excel'],
            
            // صلاحيات إضافية
            ['name' => 'examinees.view-details', 'name_ar' => 'عرض تفاصيل الممتحن الكاملة'],
            ['name' => 'examinees.change-status', 'name_ar' => 'تغيير حالة الممتحن'],
            ['name' => 'examinees.view-reports', 'name_ar' => 'عرض تقارير الممتحنين'],
        ];

        foreach ($examineePermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['name_ar' => $permission['name_ar'], 'guard_name' => 'web']
            );
        }

        // إعطاء جميع الصلاحيات للمستخدم الأول (Super Admin)
        $user = User::first();
        if ($user) {
            $allPermissions = Permission::all()->pluck('name')->toArray();
            $user->givePermissionTo($allPermissions);
        }
    }
}