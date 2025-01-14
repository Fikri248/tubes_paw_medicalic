<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ObatController extends Controller
{
    public function getData()
    {
        $obats = Obat::with('category', 'transaksis')
            ->select(['id', 'nama', 'category_id', 'jenis', 'stok_awal', 'stok_sisa', 'harga', 'deskripsi', 'created_at', 'updated_at']);

        return datatables()
            ->eloquent($obats)
            ->addIndexColumn()
            ->addColumn('kategori', function ($obat) {
                return $obat->category ? $obat->category->name : '-';
            })
            ->addColumn('stok_sisa', function ($obat) {
                // Langsung gunakan stok_sisa dari database
                return $obat->stok_sisa;
            })
            ->addColumn('actions', function ($obat) {
                return '
                <a href="' . route('master-data.obat.edit', $obat->id) . '" class="btn btn-warning btn-sm">Edit</a>
                <form action="' . route('master-data.obat.destroy', $obat->id) . '" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-name="' . e($obat->nama) . '">Hapus</button>
                </form>
            ';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function index()
    {
        $obats = Obat::with('category')->paginate(10);
        return view('master_data.obat', compact('obats'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('master_data.create_obat', compact('categories'));
    }

    public function edit($id)
    {
        // Ambil data obat termasuk relasi dengan kategori
        $obat = Obat::findOrFail($id);

        // Ambil semua kategori untuk dropdown
        $categories = Category::all();

        // Langsung gunakan stok_sisa dari database tanpa menghitung ulang
        $stokSisa = $obat->stok_sisa;

        return view('master_data.edit_obat', compact('obat', 'categories', 'stokSisa'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'jenis' => 'required|string|in:krim,salep,sirup,gel,lotion,tablet,sachet,pil,kapsul,kaplet,bubuk,oles,spray,tetes',
            'stok_sisa' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
        ]);

        $obat = Obat::findOrFail($id);
        $obat->update([
            'nama' => $request->nama,
            'category_id' => $request->category_id,
            'jenis' => $request->jenis,
            'stok_sisa' => $request->stok_sisa,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Berhasil', 'Obat berhasil diperbarui.');

        return redirect()->route('master-data.obat');
    }

    public function destroy($id)
    {
        $obat = Obat::findOrFail($id);
        $obat->delete();

        Alert::success('Berhasil', 'Obat berhasil dihapus.');
        return redirect()->route('master-data.obat');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'jenis' => 'required|string|in:krim,salep,sirup,gel,lotion,tablet,sachet,pil,kapsul,kaplet,bubuk,oles,spray,tetes',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'required|string',
        ]);

        // Set stok awal dan stok sisa sama
        Obat::create([
            'nama' => $request->nama,
            'category_id' => $request->category_id,
            'jenis' => $request->jenis,
            'stok_awal' => $request->stok,
            'stok_sisa' => $request->stok, // Stok sisa sama dengan stok awal
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Berhasil', 'Obat berhasil ditambahkan.');
        return redirect()->route('master-data.obat');
    }
}
