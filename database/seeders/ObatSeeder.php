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

        Obat::create([
            'nama' => 'Mixagrip',
            'category_id' => $categoryDalam->id,
            'jenis' => 'Tablet',
            'stok' => 99
        ]);

        Obat::create([
            'nama' => 'Promag',
            'category_id' => $categoryDalam->id,
            'jenis' => 'Sachet',
            'stok' => 88
        ]);

        Obat::create([
            'nama' => 'Betadine',
            'category_id' => $categoryLuar->id,
            'jenis' => 'Botol',
            'stok' => 77
        ]);
    }
}
