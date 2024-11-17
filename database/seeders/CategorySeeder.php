<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Traktor',
            'slug' => 'traktor'
        ]);
        Category::create([
            'name' => 'Tanam',
            'slug' => 'tanam'
        ]);
        Category::create([
            'name' => 'Pemupukan',
            'slug' => 'pemupukan'
        ]);
        Category::create([
            'name' => 'Penyemprot',
            'slug' => 'penyemprot'
        ]);
        Category::create([
            'name' => 'Pemanen',
            'slug' => 'pemanen'
        ]);
        Category::create([
            'name' => 'Irigasi',
            'slug' => 'irigasi'
        ]);
    }
}
