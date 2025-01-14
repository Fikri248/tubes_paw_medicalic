<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        // Menghitung total obat tersedia (stok sisa dari semua obat)
        $totalObatTersedia = Obat::sum('stok_sisa'); // Menjumlahkan stok sisa dari semua obat

        // Menghitung total obat yang terjual (dari tabel Transaksi)
        $totalObatTerjual = Transaksi::sum('jumlah'); // Kolom 'jumlah' menyimpan jumlah obat yang dibeli/terjual

        // Menghitung total jenis obat yang ada
        $totalSemuaObat = Obat::count(); // Menghitung total jenis obat (jumlah baris di tabel Obat)

        // Menghitung subtotal pendapatan (tanpa PPN)
        $subtotalPendapatan = Transaksi::sum('total_harga');

        // Menghitung total PPN (12% dari subtotal)
        $ppn = $subtotalPendapatan * 0.12;

        // Menghitung total pendapatan (subtotal + PPN)
        $totalPendapatan = $subtotalPendapatan + $ppn;

        // Mengirim data ke view dashboard
        return view('dashboard', compact('totalObatTersedia', 'totalObatTerjual', 'totalSemuaObat', 'totalPendapatan'));
    }
}
