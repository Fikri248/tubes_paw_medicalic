<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class KategoriController extends Controller
{
    public function index()
    {
        $pageTitle = 'Kategori Obat';
        $categories = Category::all();
        return view('master_data.kategori', compact('pageTitle', 'categories'));
    }

    public function create()
    {
        $pageTitle = 'Buat Kategori Baru';
        return view('master_data.create_kategori', compact('pageTitle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        return redirect()->route('master-data.kategori')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        // Temukan kategori berdasarkan ID
        $category = Category::findOrFail($id);

        // Hapus semua obat yang terkait dengan kategori
        $category->obat()->delete();

        // Setelah itu, hapus kategori
        $category->delete();

        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus.']);
    }
}
