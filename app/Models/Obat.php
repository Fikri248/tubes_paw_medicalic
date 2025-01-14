<?php

// app/Models/Obat.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    protected $table = 'obat'; // Nama tabel
    protected $fillable = ['nama', 'category_id', 'jenis', 'stok', 'deskripsi', 'harga']; // Kolom yang bisa diisi

    // Relasi ke model Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
