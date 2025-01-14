<?php

// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories'; // Nama tabel
    protected $fillable = ['name']; // Kolom yang bisa diisi

    public function obat()
    {
        return $this->hasMany(Obat::class);
    }
}
