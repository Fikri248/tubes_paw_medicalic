<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $fillable = [
        'obat_id',
        'nama',
        'jenis',
        'jumlah',
        'harga'
    ];

    public function obat()
    {
        return $this->belongsTo(Obat::class);
    }
}
