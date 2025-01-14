<?php

// database/seeders/ObatSeeder.php

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

        $medicines = [
            [
                'nama' => 'Mixagrip',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
            [
                'nama' => 'Promag',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Sachet',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
            [
                'nama' => 'Betadine',
                'category_id' => $categoryLuar->id,
                'jenis' => 'Botol',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
            [
                'nama' => 'Komix',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Kapsul',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
            [
                'nama' => 'Paracetamol',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
            [
                'nama' => 'Antangin',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Kapsul',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
            [
                'nama' => 'Bodrex',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Kapsul',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
            [
                'nama' => 'Formicool',
                'category_id' => $categoryLuar->id,
                'jenis' => 'Spray',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
            [
                'nama' => 'Salep',
                'category_id' => $categoryLuar->id,
                'jenis' => 'Botol',
                'stok' => 100,
                'harga' => 15000,
                'deskripsi' => 'Obat pereda flu dan pilek.',
            ],
        ];

        foreach ($medicines as $medicine) {
            Obat::create($medicine);
        }
    }
}
