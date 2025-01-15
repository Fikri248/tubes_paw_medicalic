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
        $obat = Obat::findOrFail($id);

        $categories = Category::all();

        $stokSisa = $obat->stok_sisa;

        $obat->harga = (int) $obat->harga;

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
            'nama' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/',
                'unique:obat,nama',
            ],
            'category_id' => 'required|exists:categories,id',
            'jenis' => 'required|string|in:krim,salep,sirup,gel,lotion,tablet,sachet,pil,kapsul,kaplet,bubuk,oles,spray,tetes',
            'stok' => 'required|integer|min:0',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/',
            ],
        ], [
            'nama.required' => 'Nama obat harus diisi.',
            'nama.string' => 'Nama obat harus berupa teks.',
            'nama.max' => 'Nama obat tidak boleh lebih dari 255 karakter.',
            'nama.regex' => 'Nama obat hanya boleh mengandung huruf dan spasi.',
            'nama.unique' => 'Nama obat sudah ada. Harap masukkan nama lain.',
            'category_id.required' => 'Kategori obat harus dipilih.',
            'category_id.exists' => 'Kategori obat yang dipilih tidak valid.',
            'jenis.required' => 'Jenis obat harus diisi.',
            'jenis.string' => 'Jenis obat harus berupa teks.',
            'jenis.in' => 'Jenis obat tidak valid.',
            'stok.required' => 'Stok obat harus diisi.',
            'stok.integer' => 'Stok obat harus berupa angka.',
            'stok.min' => 'Stok obat tidak boleh kurang dari 0.',
            'harga.required' => 'Harga obat harus diisi.',
            'harga.numeric' => 'Harga obat harus berupa angka.',
            'harga.min' => 'Harga obat tidak boleh kurang dari 0.',
            'deskripsi.required' => 'Deskripsi obat harus diisi.',
            'deskripsi.string' => 'Deskripsi obat harus berupa teks.',
            'deskripsi.regex' => 'Deskripsi obat hanya boleh mengandung huruf dan spasi.',
        ]);

        Obat::create([
            'nama' => $request->nama,
            'category_id' => $request->category_id,
            'jenis' => $request->jenis,
            'stok_awal' => $request->stok,
            'stok_sisa' => $request->stok, 
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
        ]);

        Alert::success('Berhasil', 'Obat berhasil ditambahkan.');
        return redirect()->route('master-data.obat');
    }
}
