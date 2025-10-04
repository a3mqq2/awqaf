<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            ['name' => 'examinees',             'name_ar' => 'ادارة الممتحنين '],
            ['name' => 'users',             'name_ar' => 'المستخدمين'],
            ['name' => 'clusters',             'name_ar' => 'ادارة التجمعات'],
            ['name' => 'offices',             'name_ar' => 'ادارة المكاتب'],
            ['name' => 'narrations',             'name_ar' => 'ادارة الروايات'],
            ['name' => 'drawings',             'name_ar' => 'ادارة رسوم المصاحف'],
        ];

        foreach ($permissions as $item) {
            Permission::firstOrCreate(
                ['name' => $item['name']],
                ['name_ar' => $item['name_ar'], 'guard_name' => 'web']
            );
        }

        $user = User::first();
        if ($user) {
            $user->givePermissionTo(collect($permissions)->pluck('name')->toArray());
        }
    }
}
