<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total obat yang masuk
        $totalObatMasuk = Obat::sum('stok');

        // Menghitung total obat yang terjual
        // $totalObatTerjual = Transaksi::sum('jumlah_terjual'); // Ganti dengan kolom yang sesuai jika ada

        // Menghitung total jumlah obat
        $totalSemuaObat = Obat::count(); // Menghitung jumlah total obat di tabel

        // Menghitung total pendapatan
        // $totalPendapatan = Transaksi::sum('total_harga'); // Ganti dengan kolom yang sesuai

        // Mengirim data ke view dashboard
        return view('dashboard', compact('totalObatMasuk', 'totalSemuaObat'));
    }
}
