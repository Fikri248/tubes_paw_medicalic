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
        // Mencari kategori berdasarkan nama
        $categoryDalam = Category::where('name', 'Obat Dalam')->first();
        $categoryLuar = Category::where('name', 'Obat Luar')->first();

        // Validasi kategori ada atau tidak
        if (!$categoryDalam || !$categoryLuar) {
            $this->command->error('Kategori Obat Dalam atau Obat Luar tidak ditemukan. Pastikan kategori sudah ditambahkan terlebih dahulu.');
            return;
        }

        // Menambahkan data obat
        Obat::create([
            'nama' => 'Mixagrip',
            'category_id' => $categoryDalam->id,
            'jenis' => 'Tablet',
            'stok' => 99,
            'harga' => 15000,
            'deskripsi' => 'Obat pereda flu dan pilek.',
        ]);

        Obat::create([
            'nama' => 'Promag',
            'category_id' => $categoryDalam->id,
            'jenis' => 'Sachet',
            'stok' => 88,
            'harga' => 15000,
            'deskripsi' => 'Obat untuk mengatasi masalah lambung.',
        ]);

        Obat::create([
            'nama' => 'Betadine',
            'category_id' => $categoryLuar->id,
            'jenis' => 'Botol',
            'stok' => 77,
            'harga' => 20000,
            'deskripsi' => 'Cairan antiseptik untuk membersihkan luka.',
        ]);
    }
}
