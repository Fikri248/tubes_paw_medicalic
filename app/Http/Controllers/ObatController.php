<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Category;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index()
    {
        $obats = Obat::with('category')->paginate(10); // Ambil semua obat beserta kategori yang terkait
        return view('master_data.obat', compact('obats'));
    }

    public function create()
    {
        // Mengambil data kategori untuk dropdown
        $categories = Category::all();

        return view('master_data.create_obat', compact('categories'));
    }

    public function edit($id)
    {
        $obat = Obat::findOrFail($id);
        $categories = Category::all(); // Kategori untuk dropdown
        return view('master_data.edit_obat', compact('obat', 'categories'));
    }
    public function update(Request $request, $id)
{
    // Validasi input
    $request->validate([
        'nama' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'jenis' => 'required|string|in:krim,salep,sirup,gel,lotion,tablet,sachet,pil,kapsul,kaplet,bubuk,oles,spray,tetes',
        'stok' => 'required|integer',
        'harga' => 'required|numeric|min:0', // Validasi harga
        'deskripsi' => 'required|string',
    ]);

    $obat = Obat::findOrFail($id);
    $obat->update([
        'nama' => $request->nama,
        'category_id' => $request->category_id,
        'jenis' => $request->jenis,
        'stok' => $request->stok,
        'harga' => $request->harga, // Update harga
        'deskripsi' => $request->deskripsi,
    ]);

    return redirect()->route('master-data.obat')->with('success', 'Obat berhasil diperbarui!');
}



    public function destroy($id)
    {
        $obat = Obat::findOrFail($id); // Cari data berdasarkan ID
        $obat->delete(); // Hapus data
        return redirect()->route('master-data.obat')->with('success', 'Obat berhasil dihapus.');
    }


    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'jenis' => 'required|string|in:krim,salep,sirup,gel,lotion,tablet,sachet,pil,kapsul,kaplet,bubuk,oles,spray,tetes', // Pastikan jenis obat valid
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string',
        ]);

        // Menyimpan data obat ke database
        Obat::create([
            'nama' => $request->nama,
            'category_id' => $request->category_id,
            'jenis' => $request->jenis,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('master-data.obat')->with('success', 'Obat berhasil ditambahkan!');
    }
}
