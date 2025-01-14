<?php

namespace Database\Seeders;

use App\Models\Obat;
use App\Models\Transaksi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicines = [
            [
                'obat_id' => 1,
                'nama' => 'Mixagrip',
                'jenis' => 'Tablet',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
            [
                'obat_id' => 2,
                'nama' => 'Promag',
                'jenis' => 'Sachet',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
            [
                'obat_id' => 3,
                'nama' => 'Betadine',
                'jenis' => 'Botol',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
            [
                'obat_id' => 4,
                'nama' => 'Komix',
                'jenis' => 'Kapsul',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
            [
                'obat_id' => 5,
                'nama' => 'Paracetamol',
                'jenis' => 'Tablet',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
            [
                'obat_id' => 6,
                'nama' => 'Antangin',
                'jenis' => 'Kapsul',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
            [
                'obat_id' => 7,
                'nama' => 'Bodrex',
                'jenis' => 'Kapsul',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
            [
                'obat_id' => 8,
                'nama' => 'Formicool',
                'jenis' => 'Spray',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
            [
                'obat_id' => 9,
                'nama' => 'Salep',
                'jenis' => 'Botol',
                'jumlah' => 10,
                'harga' => 15000 * 10,
            ],
        ];

        foreach ($medicines as $medicine) {
            Transaksi::create($medicine);
        }
    }
}
