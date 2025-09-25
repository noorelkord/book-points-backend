<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\College;
use App\Models\MeetingPoint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->first() ?? User::factory()->create([
            'name' => 'Seeder Owner',
            'email' => 'owner@example.com',
            'password' => bcrypt('password'),
        ]);

        $college = College::query()->first();
        if (!$college) {
            return; // Colleges are expected to be seeded beforehand
        }

        $meetingPoints = MeetingPoint::query()->with('location')->get();
        if ($meetingPoints->isEmpty()) {
            return; // Meeting points are expected to be seeded beforehand
        }

        // Arabic course titles
        $bookTitles = [
            'أنظمة تشغيل',
            'نظم مغموسة',
            'جافا ١',
            'جافا ٢',
            'قواعد بيانات',
            'تراكيب بيانات وخوارزميات',
            'ذكاء اصطناعي',
            'شبكات الحاسوب',
            'تحليل عددي',
            'معالجة الصور',
            'رياضيات متقطعة',
        ];

        $flashTitles = [
            'فلاش كارد - أنظمة تشغيل',
            'فلاش كارد - نظم مغموسة',
            'فلاش كارد - جافا ١',
            'فلاش كارد - جافا ٢',
            'فلاش كارد - قواعد بيانات',
            'فلاش كارد - تراكيب بيانات وخوارزميات',
            'فلاش كارد - ذكاء اصطناعي',
            'فلاش كارد - شبكات الحاسوب',
            'فلاش كارد - تحليل عددي',
            'فلاش كارد - معالجة الصور',
            'فلاش كارد - رياضيات متقطعة',
        ];

        $now = now();
        $rows = [];

        // Clear existing items to avoid duplicates when reseeding
        DB::table('items')->delete();

        foreach ($meetingPoints as $mp) {
            foreach ($bookTitles as $title) {
                $rows[] = [
                    'owner_id' => $owner->id,
                    'type' => 'book',
                    'title' => $title,
                    'stage' => 'Year 3',
                    'college_id' => $college->id,
                    'location_id' => $mp->location_id,
                    'meeting_point_id' => $mp->id,
                    'image_url' => null,
                    'description' => 'كتاب: ' . $title . ' - متاح في ' . $mp->name,
                    'status' => 'available',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            foreach ($flashTitles as $title) {
                $rows[] = [
                    'owner_id' => $owner->id,
                    'type' => 'flash',
                    'title' => $title,
                    'stage' => 'Year 3',
                    'college_id' => $college->id,
                    'location_id' => $mp->location_id,
                    'meeting_point_id' => $mp->id,
                    'image_url' => null,
                    'description' => 'بطاقات: ' . $title . ' - متاحة في ' . $mp->name,
                    'status' => 'available',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if (!empty($rows)) {
            DB::table('items')->insert($rows);
        }
    }
}


