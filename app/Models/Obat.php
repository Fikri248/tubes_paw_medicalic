<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat';
    protected $fillable = [
        'nama',
        'category_id',
        'jenis',
        'stok_awal',
        'stok_sisa',
        'harga',
        'deskripsi',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id'); // Kolom foreign key adalah 'category_id'
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class, 'obat_id', 'id');
    }

    public function setStokAwalAttribute($value)
    {
        if (!$this->exists) { // Hanya set stok awal jika data baru
            $this->attributes['stok_awal'] = $value;
        }
    }
}
