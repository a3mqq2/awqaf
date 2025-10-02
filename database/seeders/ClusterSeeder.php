<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cluster;

class ClusterSeeder extends Seeder
{
    public function run(): void
    {
        $clusters = [
            'سرت',
            'الأبيار',
            'بنغازي',
            'طبرق',
            'سبها',
            'البيضاء',
            'اجدابيا',
            'جالو',
            'درنة',
        ];

        foreach ($clusters as $name) {
            Cluster::firstOrCreate(['name' => $name], ['is_active' => true]);
        }
    }
}