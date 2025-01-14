<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = ['order_id', 'obat_id', 'jumlah', 'total_harga', 'created_at', 'updated_at'];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
