<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'title' => 'Berthoud Raptor Sprayer',
            'price' => 500,
            'quantity' => 2,
            'category_id' => 4,
            'location_id' => 2,
            'description' => 'Penyemprot mandiri Berthoud Raptor AS tersedia dalam kapasitas 4200 L dengan jenis tangkai dengan panjang hingga 42 m.'
        ]);
    }
}
