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
    $subtotalPendapatan = Transaksi::sum('total_harga');

    $totalPPN = 0.12 * $subtotalPendapatan;

    $pendapatanBersih = $subtotalPendapatan;

    $obatTerlaris = Transaksi::with('obat')
        ->selectRaw('obat_id, SUM(jumlah) as total_jumlah')
        ->groupBy('obat_id')
        ->orderBy('total_jumlah', 'desc')
        ->first();

    $obatTerlarisNama = $obatTerlaris->obat->nama ?? 'Tidak ada';

    $jumlahTransaksi = Transaksi::distinct('order_id')->count('order_id');

    // Data untuk chart jumlah penjualan obat
    $chartLabelsPenjualan = Transaksi::join('obat', 'transaksi.obat_id', '=', 'obat.id')
    ->selectRaw('obat.nama as obat_nama, SUM(transaksi.jumlah) as total_jumlah')
    ->groupBy('transaksi.obat_id')
    ->orderBy('total_jumlah', 'desc')
    ->pluck('obat_nama')
    ->toArray();

$chartDataPenjualan = Transaksi::join('obat', 'transaksi.obat_id', '=', 'obat.id')
    ->selectRaw('SUM(transaksi.jumlah) as total_jumlah')
    ->groupBy('transaksi.obat_id')
    ->orderBy('total_jumlah', 'desc')
    ->pluck('total_jumlah')
    ->toArray();

$chartLabelsPendapatan = Transaksi::join('obat', 'transaksi.obat_id', '=', 'obat.id')
    ->selectRaw('obat.nama as obat_nama, SUM(transaksi.total_harga) as total_harga')
    ->groupBy('transaksi.obat_id')
    ->orderBy('total_harga', 'desc')
    ->pluck('obat_nama')
    ->toArray();

$chartDataPendapatan = Transaksi::join('obat', 'transaksi.obat_id', '=', 'obat.id')
    ->selectRaw('SUM(transaksi.total_harga) as total_harga')
    ->groupBy('transaksi.obat_id')
    ->orderBy('total_harga', 'desc')
    ->pluck('total_harga')
    ->toArray();


    return view('laporan.index', [
        'pendapatanBersih' => $pendapatanBersih,
        'ppn' => $totalPPN,
        'obatTerlaris' => $obatTerlarisNama,
        'jumlahTransaksi' => $jumlahTransaksi,
        'chartLabelsPenjualan' => $chartLabelsPenjualan,
        'chartDataPenjualan' => $chartDataPenjualan,
        'chartLabelsPendapatan' => $chartLabelsPendapatan,
        'chartDataPendapatan' => $chartDataPendapatan,
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
        return Excel::download(new ObatExport, 'laporan_obat-' . date('d-m-Y') . '.xlsx');
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

        return Excel::download(new TransaksiExport($data), 'laporan_transaksi-' . date('d-m-Y') . '.xlsx');
    }
}
