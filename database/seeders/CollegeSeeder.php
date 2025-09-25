<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\College;

class CollegeSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['code' => 'ENG', 'name' => 'الهندسة',  'is_active' => 1],
            ['code' => 'SCI', 'name' => 'العلوم',   'is_active' => 1],
            ['code' => 'MED', 'name' => 'الطب',     'is_active' => 1],
            ['code' => 'BUS', 'name' => 'التجارة',  'is_active' => 1],
        ];

        foreach ($rows as $r) {
            College::updateOrInsert(
                ['code' => $r['code']],     
                [
                    'name'      => $r['name'],
                    'is_active' => $r['is_active'] ?? 1,
                    'updated_at'=> now(),
                    'created_at'=> now(), 
                ]
            );
        }
    }
}
