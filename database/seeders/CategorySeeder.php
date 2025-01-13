<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Data kategori awal
        $categories = [
            ['name' => 'Obat Dalam'],
            ['name' => 'Obat Luar'],
        ];

        foreach ($categories as $category) {
            // Periksa apakah kategori sudah ada
            Category::firstOrCreate(
                ['name' => $category['name']], // Kondisi unik
                ['created_at' => now(), 'updated_at' => now()] // Data default
            );
        }
    }
}
