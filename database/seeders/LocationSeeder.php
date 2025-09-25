<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Location;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::insert([
            ['name' => 'غزة'],
            ['name' => 'دير البلح'],
            ['name' => ' خانيونس'],
            ['name' => 'رفح'],
        ]);
    }
}
