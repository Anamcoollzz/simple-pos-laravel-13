<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan', 'description' => 'Produk makanan siap saji'],
            ['name' => 'Minuman', 'description' => 'Aneka minuman segar'],
            ['name' => 'Snack', 'description' => 'Camilan ringan'],
            ['name' => 'Kebutuhan Harian', 'description' => 'Barang kebutuhan sehari-hari'],
            ['name' => 'Elektronik', 'description' => 'Perangkat elektronik kecil'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
