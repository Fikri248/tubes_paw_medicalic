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
            Category::create($category);
        }

        // Data kategori acak
        $randomCategories = [];

        for ($i = 1; $i <= 100; $i++) {
            $randomCategories[] = [
                'name' => 'Kategori ' . $i,
            ];
        }

        foreach ($randomCategories as $category) {
            Category::create($category);
        }
    }
}
