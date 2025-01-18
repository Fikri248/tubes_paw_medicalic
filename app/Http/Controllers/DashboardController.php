<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Obat Tersedia
        $totalObatTersedia = Obat::sum('stok_sisa');

        // Total Obat Terjual
        $totalObatTerjual = Transaksi::sum('jumlah');

        // Total Semua Obat
        $totalSemuaObat = Obat::count();

        // Total Pendapatan
        $subtotalPendapatan = Transaksi::sum('total_harga');
        $ppn = $subtotalPendapatan * 0.12;
        $totalPendapatan = $subtotalPendapatan + $ppn;

        // Statistik jumlah stok masing-masing nama obat
        $chartLabels = Obat::pluck('nama'); // Ambil nama obat
        $chartData = Obat::pluck('stok_sisa'); // Ambil stok sisa obat

        return view('dashboard', compact(
            'totalObatTersedia',
            'totalObatTerjual',
            'totalSemuaObat',
            'totalPendapatan',
            'chartLabels',
            'chartData'
        ));
    }
}

