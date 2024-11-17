<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create([
            'name' => 'Jawa Barat',
            'slug' => 'jawa barat'
        ]);
        Location::create([
            'name' => 'Jawa Timur',
            'slug' => 'jawa timur'
        ]);
        Location::create([
            'name' => 'Jawa Tengah',
            'slug' => 'jawa tengah'
        ]);
        Location::create([
            'name' => 'Banten',
            'slug' => 'banten'
        ]);
    }
}
