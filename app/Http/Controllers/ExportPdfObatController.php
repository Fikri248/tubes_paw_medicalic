<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExportPdfObatController extends Controller
{
    public function index()
    {
        $medicines = Obat::with('category')->get();
        $totalPrice = $medicines->sum('harga');

        return view('laporan.obat', compact('medicines', 'totalPrice'));
    }

    public function exportPdf()
    {
        $medicines = Obat::with('category')->get();
        $totalPrice = $medicines->sum('harga');

        $pdf = Pdf::loadView('laporan.obat', compact('medicines', 'totalPrice'));

        return $pdf->download('laporan obat - ' . date('Y-m-d') . '.pdf');
    }
}
