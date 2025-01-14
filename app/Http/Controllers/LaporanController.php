<?php

namespace App\Http\Controllers;

use App\Exports\ObatExport;
use App\Exports\TransaksiExport;
use App\Models\Obat;
use App\Models\Transaksi;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
{
    // 1. Subtotal pendapatan (total_harga dari semua transaksi)
    $subtotalPendapatan = Transaksi::sum('total_harga');

    // 2. Total PPN (12% dari subtotal pendapatan)
    $ppn = $subtotalPendapatan * 0.12;

    // 3. Total pendapatan bersih (subtotal + total PPN)
    $pendapatanBersih = $subtotalPendapatan;

    // 4. Obat terlaris (obat dengan jumlah pembelian terbanyak)
    $obatTerlaris = Transaksi::with('obat')
        ->selectRaw('obat_id, SUM(jumlah) as total_jumlah')
        ->groupBy('obat_id')
        ->orderBy('total_jumlah', 'desc')
        ->first();

    $obatTerlarisNama = $obatTerlaris->obat->nama ?? 'Tidak ada';

    // 5. Jumlah transaksi (jumlah unik order_id)
    $jumlahTransaksi = Transaksi::distinct('order_id')->count('order_id');

    // Kirim data ke view
    return view('laporan.index', [
        'pendapatanBersih' => $pendapatanBersih,
        'ppn' => $ppn,
        'obatTerlaris' => $obatTerlarisNama,
        'jumlahTransaksi' => $jumlahTransaksi,
    ]);
}


    public function cetakObatPdf()
{
    $obats = Obat::with('category', 'transaksis')->get();
    $pdf = PDF::loadView('laporan.export_pdf_obat', compact('obats'));
    return $pdf->download('laporan_daftar_obat.pdf');
}

public function cetakTransaksiPdf()
{
    $orders = Order::with(['transaksi.obat'])->get();

    $data = $orders->map(function ($order, $index) {
        $groupedObat = $order->transaksi->groupBy('obat_id')->map(function ($transaksis) {
            $namaObat = $transaksis->first()->obat->nama;
            $totalJumlah = $transaksis->sum('jumlah');
            return [
                'nama_obat' => $namaObat,
                'jumlah_obat' => $totalJumlah,
            ];
        });

        $namaObat = $groupedObat->pluck('nama_obat')->implode(', ');
        $jumlahObat = $groupedObat->pluck('jumlah_obat')->implode(', ');

        return [
            'no' => $index + 1,
            'nama_obat' => $namaObat,
            'jumlah_obat' => $jumlahObat,
            'total_jumlah_obat' => $order->transaksi->sum('jumlah'),
            'total_harga' => $order->total_harga,
            'waktu_pembelian' => $order->created_at->addHours(7)->format('d/m/Y, H.i'),
        ];
    });

    $pdf = Pdf::loadView('laporan.export_pdf_transaksi', ['data' => $data]);
    return $pdf->download('laporan-transaksi.pdf');
}


public function cetakObatExcel()
    {
        return Excel::download(new ObatExport, 'laporan_daftar_obat.xlsx');
    }

    public function cetakTransaksiExcel()
{
    $orders = Order::with(['transaksi.obat'])->get();

    $data = $orders->map(function ($order, $index) {
        $groupedObat = $order->transaksi->groupBy('obat_id')->map(function ($transaksis) {
            $namaObat = $transaksis->first()->obat->nama;
            $totalJumlah = $transaksis->sum('jumlah');
            return [
                'nama_obat' => $namaObat,
                'jumlah_obat' => $totalJumlah,
            ];
        });

        $namaObat = $groupedObat->pluck('nama_obat')->implode(', ');
        $jumlahObat = $groupedObat->pluck('jumlah_obat')->implode(', ');

        return [
            'no' => $index + 1,
            'nama_obat' => $namaObat,
            'jumlah_obat' => $jumlahObat,
            'total_jumlah_obat' => $order->transaksi->sum('jumlah'),
            'total_harga' => $order->total_harga,
            'waktu_pembelian' => $order->created_at->addHours(7)->format('d/m/Y, H.i'),
        ];
    });

    return Excel::download(new TransaksiExport($data), 'laporan-transaksi.xlsx');
}

}
