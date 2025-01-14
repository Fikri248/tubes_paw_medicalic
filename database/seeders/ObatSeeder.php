<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Obat;
use App\Models\Category;

class ObatSeeder extends Seeder
{
    public function run()
    {
        $categoryDalam = Category::where('name', 'Obat Dalam')->first();
        $categoryLuar = Category::where('name', 'Obat Luar')->first();

        Obat::create([
            'nama' => 'Mixagrip',
            'category_id' => $categoryDalam->id,
            'jenis' => 'Tablet',
            'stok_awal' => 99,
            'stok_sisa' => 99,
            'harga' => 15000,
            'deskripsi' => 'Obat pereda flu dan pilek.',
        ]);

        Obat::create([
            'nama' => 'Promag',
            'category_id' => $categoryDalam->id,
            'jenis' => 'Sachet',
            'stok_awal' => 88,
            'stok_sisa' => 88,
            'harga' => 15000,
            'deskripsi' => 'Obat pereda flu dan pilek.',
        ]);

        Obat::create([
            'nama' => 'Betadine',
            'category_id' => $categoryLuar->id,
            'jenis' => 'Botol',
            'stok_awal' => 77,
            'stok_sisa' => 77,
            'harga' => 15000,
            'deskripsi' => 'Obat pereda flu dan pilek.',
        ]);
    }
}
