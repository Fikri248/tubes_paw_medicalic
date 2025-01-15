<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {

        $totalObatTersedia = Obat::sum('stok_sisa');

        $totalObatTerjual = Transaksi::sum('jumlah');

        $totalSemuaObat = Obat::count();

        $subtotalPendapatan = Transaksi::sum('total_harga');

        $ppn = $subtotalPendapatan * 0.12;

        $totalPendapatan = $subtotalPendapatan + $ppn;

        return view('dashboard', compact('totalObatTersedia', 'totalObatTerjual', 'totalSemuaObat', 'totalPendapatan'));
    }
}
