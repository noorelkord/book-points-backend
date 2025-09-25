<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;
use App\Models\MeetingPoint;

class MeetingPointSeeder extends Seeder
{
    public function run(): void
    {
        $deir  = Location::where('name', 'دير البلح')->value('id');
        $kh    = Location::where('name', 'خانيونس')->value('id');
        $gaza  = Location::where('name', 'غزة')->value('id');
        $rafah = Location::where('name', 'رفح')->value('id');

        $rows = [];

        // دير البلح (زي ما كتبتي)
        if ($deir) {
            $rows = array_merge($rows, [
                ['location_id' => $deir, 'name' => 'بلدية دير البلح'],
                ['location_id' => $deir, 'name' => 'مملكة الأطفال'],
                ['location_id' => $deir, 'name' => '-دوار المدفع -البلد'],
                ['location_id' => $deir, 'name' => '-دوار 17 -البحر'],
            ]);
        }

        // خانيونس (الاختيارات اللي عبّيتيها)
        if ($kh) {
            $rows = array_merge($rows, [
                ['location_id' => $kh, 'name' => 'جامعة الأقصى'],
                ['location_id' => $kh, 'name' => 'مستشفى ناصر'],
                ['location_id' => $kh, 'name' => 'منتزه البلدية'],
                ['location_id' => $kh, 'name' => 'كنج بيتش'],
            ]);
        }

        // غزة (زي ما عبّيتي)
        if ($gaza) {
            $rows = array_merge($rows, [
                ['location_id' => $gaza, 'name' => 'السرايا'],
                ['location_id' => $gaza, 'name' => 'الصناعة'],
                ['location_id' => $gaza, 'name' => 'الميناء'],
                ['location_id' => $gaza, 'name' => 'كابتال مول'],
            ]);
        }

        // رفح
        if ($rafah) {
            $rows = array_merge($rows, [
                ['location_id' => $rafah, 'name' => 'دوار العودة'],
                ['location_id' => $rafah, 'name' => 'السور'],
                ['location_id' => $rafah, 'name' => 'دوار النجمة'],
                ['location_id' => $rafah, 'name' => 'شارع البحر'],
            ]);
        }

        // منع التكرار (على فهرس ['location_id','name'])
        MeetingPoint::upsert($rows, ['location_id', 'name'], []);
    }
}
