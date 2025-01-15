<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use RealRashid\SweetAlert\Facades\Alert;

class KategoriController extends Controller
{
    public function getData()
    {
        $categories = Category::withCount('obat')->get();

        return datatables()
            ->collection($categories)
            ->addIndexColumn()
            ->addColumn('jumlah_obat', function ($category) {
                return $category->obat_count;
            })
            ->addColumn('actions', function ($category) {
                return '
                <form action="' . route('master-data.kategori.destroy', $category->id) . '" method="POST" class="d-inline delete-form">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button type="button" class="btn btn-danger btn-sm btn-delete" data-name="' . $category->name . '">
                        Hapus
                    </button>
                </form>
            ';
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

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
            'name' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s]+$/',
                'unique:categories,name',
                'max:255'
            ]
        ], [
            'name.required' => 'Kategori wajib diisi.',
            'name.string' => 'Kategori harus berupa teks.',
            'name.regex' => 'Kategori hanya boleh berisi huruf dan spasi.',
            'name.unique' => 'Kategori sudah ada.',
            'name.max' => 'Kategori tidak boleh lebih dari 255 karakter.'
        ]);

        Category::create([
            'name' => $request->name,
        ]);

        Alert::success('Berhasil', 'Kategori berhasil ditambahkan.');
        return redirect()->route('master-data.kategori');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        Alert::success('Berhasil', 'Kategori berhasil dihapus.');
        return redirect()->route('master-data.kategori');
    }
}
