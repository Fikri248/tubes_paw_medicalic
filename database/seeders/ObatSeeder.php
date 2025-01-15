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

        $dataObat = [
            [
                'nama' => 'Paracetamol',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'harga' => 2000,
                'deskripsi' => 'Obat penurun demam dan pereda nyeri.',
            ],
            [
                'nama' => 'Antangin',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Sachet',
                'harga' => 3000,
                'deskripsi' => 'Obat herbal untuk masuk angin.',
            ],
            [
                'nama' => 'Amoxicillin',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Kapsul',
                'harga' => 5000,
                'deskripsi' => 'Antibiotik untuk infeksi bakteri.',
            ],
            [
                'nama' => 'Betadine',
                'category_id' => $categoryLuar->id,
                'jenis' => 'Botol',
                'harga' => 10000,
                'deskripsi' => 'Antiseptik untuk luka.',
            ],
            [
                'nama' => 'Salonpas',
                'category_id' => $categoryLuar->id,
                'jenis' => 'Plester',
                'harga' => 5000,
                'deskripsi' => 'Pereda nyeri otot.',
            ],
            [
                'nama' => 'Vitamin C',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'harga' => 1500,
                'deskripsi' => 'Vitamin untuk daya tahan tubuh.',
            ],
            [
                'nama' => 'Diapet',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'harga' => 2500,
                'deskripsi' => 'Obat untuk diare.',
            ],
            [
                'nama' => 'Caladine',
                'category_id' => $categoryLuar->id,
                'jenis' => 'Lotion',
                'harga' => 12000,
                'deskripsi' => 'Pereda gatal dan iritasi kulit.',
            ],
            [
                'nama' => 'Tolak Angin',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Sachet',
                'harga' => 4000,
                'deskripsi' => 'Obat herbal untuk masuk angin.',
            ],
            [
                'nama' => 'Bodrex',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'harga' => 2500,
                'deskripsi' => 'Obat pereda sakit kepala.',
            ],
            [
                'nama' => 'Minyak Kayu Putih',
                'category_id' => $categoryLuar->id,
                'jenis' => 'Botol',
                'harga' => 15000,
                'deskripsi' => 'Minyak untuk menghangatkan tubuh.',
            ],
            [
                'nama' => 'Mixagrip',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'harga' => 3000,
                'deskripsi' => 'Obat flu dan pilek.',
            ],
            [
                'nama' => 'Promag',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'harga' => 2500,
                'deskripsi' => 'Obat maag dan perut kembung.',
            ],
            [
                'nama' => 'Dermatix',
                'category_id' => $categoryLuar->id,
                'jenis' => 'Gel',
                'harga' => 85000,
                'deskripsi' => 'Gel untuk menghilangkan bekas luka.',
            ],
            [
                'nama' => 'Neurobion',
                'category_id' => $categoryDalam->id,
                'jenis' => 'Tablet',
                'harga' => 6000,
                'deskripsi' => 'Vitamin untuk kesehatan saraf.',
            ],
        ];

        foreach ($dataObat as $data) {
            Obat::create([
                'nama' => $data['nama'],
                'category_id' => $data['category_id'],
                'jenis' => $data['jenis'],
                'stok_awal' => 25,
                'stok_sisa' => 25,
                'harga' => $data['harga'],
                'deskripsi' => $data['deskripsi'],
            ]);
        }
    }
}
