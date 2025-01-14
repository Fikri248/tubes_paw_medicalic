<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ObatExport implements FromQuery, WithHeadings
{
    public function query()
    {
        return Obat::with('category', 'transaksis')
            ->select(['id', 'nama', 'category_id', 'jenis', 'stok_awal', 'stok_sisa', 'harga', 'deskripsi']);
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Obat',
            'Kategori',
            'Jenis',
            'Stok Awal',
            'Stok Sisa',
            'Harga',
            'Deskripsi',
        ];
    }
}
