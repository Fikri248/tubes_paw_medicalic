<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $transactions = Transaksi::all();
        $priceTotal = $transactions->sum('harga');
        return view('transaksi.index', compact('transactions', 'priceTotal'));
    }

    public function create()
    {
        $obat = Obat::all();
        return view('transaksi.create', compact('obat'));
    }

    public function getSelectedObat(Obat $obat)
    {

        return response()->json($obat);

        return response()->json([
            'id' => $obat->id,
            'stok' => $obat->stok,
            'harga' => $obat->harga,
            'jenis' => $obat->jenis
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string',
            'jumlah' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $obat = Obat::find($request->obat_id);

        if ($obat->stok < $request->jumlah) {
            return redirect()->route('transaksi.obat.create')->with('error', 'Stok obat tidak mencukupi.');
        }

        $obat->stok -= $request->jumlah;
        $obat->save();

        Transaksi::create([
            'obat_id' => $request->obat_id,
            'nama' => $obat->nama,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga,
        ]);

        return redirect()->route('transaksi.obat.index')->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function edit(Transaksi $transaksi)
    {
        $obat = Obat::all();
        $transaksi = $transaksi->load('obat');
        return view('transaksi.edit', compact('transaksi', 'obat'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $request->validate([
            'obat_id' => 'required|exists:obat,id',
            'nama' => 'required|string|max:255',
            'jenis' => 'required|string',
            'jumlah' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $obat = Obat::find($request->obat_id);

        if ($obat->stok < $request->jumlah) {
            return redirect()->route('transaksi.obat.edit', $transaksi)->with('error', 'Stok obat tidak mencukupi.');
        }

        $obat->stok -= $request->jumlah;

        $obat->save();

        $transaksi->update([
            'obat_id' => $request->obat_id,
            'nama' => $transaksi->nama,
            'jenis' => $request->jenis,
            'jumlah' => $request->jumlah,
            'harga' => $request->harga
        ]);

        return redirect()->route('transaksi.obat.index')->with('success', 'Transaksi berhasil diperbarui!');
    }

    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();
        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus.']);
    }
}
