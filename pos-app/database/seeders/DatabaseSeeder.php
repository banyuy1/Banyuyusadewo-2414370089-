<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Kasir Default
        User::factory()->create([
            'name' => 'Kasir',
            'email' => 'kasir@toko.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Buat Kategori Makanan
        $category = Category::create(['name' => 'Makanan']);

        // 3. Masukkan 10 Menu Makanan Default
        $menuMakanan = [
            ['name' => 'Nasi Goreng Spesial', 'price' => 20000, 'stock' => 50],
            ['name' => 'Mie Ayam Bakso', 'price' => 18000, 'stock' => 40],
            ['name' => 'Ayam Goreng Lalapan', 'price' => 22000, 'stock' => 35],
            ['name' => 'Sate Ayam (10 Tusuk)', 'price' => 25000, 'stock' => 30],
            ['name' => 'Bakso Mercon', 'price' => 17000, 'stock' => 45],
            ['name' => 'Gado-Gado Betawi', 'price' => 15000, 'stock' => 25],
            ['name' => 'Soto Ayam Madura', 'price' => 16000, 'stock' => 30],
            ['name' => 'Roti Bakar Cokelat', 'price' => 12000, 'stock' => 20],
            ['name' => 'Pisang Goreng Keju', 'price' => 10000, 'stock' => 20],
            ['name' => 'Pempek Kapal Selam', 'price' => 18000, 'stock' => 15],
        ];

        foreach ($menuMakanan as $makanan) {
            Product::create([
                'category_id' => $category->id,
                'name' => $makanan['name'],
                'price' => $makanan['price'],
                'stock' => $makanan['stock']
            ]);
        }
    }
}