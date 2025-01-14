<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportPdfTransaksiController extends Controller
{
    public function index()
    {
        $transactions = Transaksi::with('obat')->get();
        $totalPrice = $transactions->sum('harga');

        return view('laporan.transaksi', compact('transactions', 'totalPrice'));
    }
    public function exportPdf()
    {
        $transactions = Transaksi::with('obat')->get();
        $totalPrice = $transactions->sum('harga');

        $pdf = Pdf::loadView('laporan.transaksi', compact('transactions', 'totalPrice'));

        return $pdf->download('laporan transaksi penjualan - ' . date('Y-m-d') . '.pdf');
    }
}
