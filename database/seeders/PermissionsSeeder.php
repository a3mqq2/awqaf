<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions
        $permissions = [
            // Examinees
            'examinees.view',
            'examinees.create',
            'examinees.edit',
            'examinees.delete',
            'examinees.export',
            
            // Attendance
            'attendance.mark',
            'attendance.view',
            
            // Clusters
            'clusters',
            
            // Offices
            'offices',
            
            // Narrations
            'narrations',
            
            // Drawings
            'drawings',
            
            // Committees (للـ Manager فقط)
            'committees.view',
            'committees.create',
            'committees.edit',
            'committees.delete',
            
            // Judges (للـ Manager فقط)
            'judges.view',
            'judges.create',
            'judges.edit',
            'judges.delete',
            
            // Users (للـ Admin فقط)
            'users',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            
            // System (للـ Admin فقط)
            'backup',
            'system_logs',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ========== Admin Role ========== 
        // الأدمن: إدارة النظام، المستخدمين، النسخ الاحتياطي - بدون اللجان والمحكمين
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->syncPermissions([
            'examinees.view',
            'examinees.create',
            'examinees.edit',
            'examinees.delete',
            'examinees.export',
            'attendance.mark',
            'attendance.view',
            'clusters',
            'offices',
            'narrations',
            'drawings',
            'users',
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'backup',
            'system_logs',
        ]);

        // ========== Manager Role ========== 
        // المدير: كل شيء ما عدا إدارة المستخدمين والنظام
        $manager = Role::firstOrCreate(['name' => 'manager']);
        $manager->syncPermissions([
            'examinees.view',
            'examinees.create',
            'examinees.edit',
            'examinees.delete',
            'examinees.export',
            'attendance.mark',
            'attendance.view',
            'clusters',
            'offices',
            'narrations',
            'drawings',
            'committees.view',
            'committees.create',
            'committees.edit',
            'committees.delete',
            'judges.view',
            'judges.create',
            'judges.edit',
            'judges.delete',
        ]);

        // ========== Office Role ========== 
        $office = Role::firstOrCreate(['name' => 'office']);
        $office->syncPermissions([
            'examinees.view',
            'examinees.create',
            'examinees.edit',
        ]);

        // ========== Judge Role ========== 
        $judge = Role::firstOrCreate(['name' => 'judge']);
        // المحكمون لا يحتاجون صلاحيات خاصة

        // ========== Viewer Role (اختياري) ========== 
        $viewer = Role::firstOrCreate(['name' => 'viewer']);
        $viewer->syncPermissions([
            'examinees.view',
            'attendance.view',
        ]);

        $this->command->info('✅ Permissions and Roles created successfully!');
        $this->command->info('📋 Admin: System, Users, Backup (NO Committees/Judges)');
        $this->command->info('📋 Manager: Examinees, Committees, Judges (NO Users/System)');
    }
}