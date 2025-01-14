<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = ['name'];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'category_id', 'id'); // Kolom foreign key adalah 'category_id'
    }
}
