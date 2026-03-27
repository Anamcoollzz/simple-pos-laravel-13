<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Nasi Goreng Spesial', 'category' => 'Makanan', 'price' => 28000, 'stock' => 25, 'description' => 'Nasi goreng dengan telur dan ayam suwir'],
            ['name' => 'Mie Ayam Bakso', 'category' => 'Makanan', 'price' => 22000, 'stock' => 30, 'description' => 'Mie ayam dengan bakso sapi'],
            ['name' => 'Es Teh Manis', 'category' => 'Minuman', 'price' => 8000, 'stock' => 100, 'description' => 'Es teh manis dingin'],
            ['name' => 'Kopi Susu Gula Aren', 'category' => 'Minuman', 'price' => 18000, 'stock' => 50, 'description' => 'Kopi susu creamy dengan gula aren'],
            ['name' => 'Keripik Singkong Pedas', 'category' => 'Snack', 'price' => 12000, 'stock' => 40, 'description' => 'Keripik singkong rasa pedas'],
            ['name' => 'Biskuit Cokelat', 'category' => 'Snack', 'price' => 15000, 'stock' => 35, 'description' => 'Biskuit renyah isi cokelat'],
            ['name' => 'Sabun Cuci Tangan', 'category' => 'Kebutuhan Harian', 'price' => 14000, 'stock' => 45, 'description' => 'Sabun cair antibakteri'],
            ['name' => 'Lampu LED 9W', 'category' => 'Elektronik', 'price' => 25000, 'stock' => 20, 'description' => 'Lampu LED hemat energi'],
            ['name' => 'Kabel Charger Type-C', 'category' => 'Elektronik', 'price' => 30000, 'stock' => 28, 'description' => 'Kabel charger cepat Type-C'],
            ['name' => 'Air Mineral 600ml', 'category' => 'Minuman', 'price' => 5000, 'stock' => 120, 'description' => 'Air mineral botol 600ml'],
        ];

        foreach ($products as $item) {
            $category = Category::where('name', $item['category'])->first();

            if (!$category) {
                continue;
            }

            Product::updateOrCreate(
                ['name' => $item['name']],
                [
                    'category_id' => $category->id,
                    'price' => $item['price'],
                    'stock' => $item['stock'],
                    'description' => $item['description'],
                ]
            );
        }
    }
}
