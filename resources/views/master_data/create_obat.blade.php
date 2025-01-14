@extends('layouts.app')

@section('title', 'Tambah Obat')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Tambah Obat Baru</h1>
        <a href="{{ route('master-data.obat') }}" class="btn btn-secondary">
            <i class="bi-arrow-left-circle me-2"></i> Kembali ke Daftar Obat
        </a>
    </div>

    <form action="{{ route('master-data.obat.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Obat</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <div class="dropdown-container">
                <select class="form-control dropdown-trigger" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </div>
        </div>

        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Obat</label>
            <div class="dropdown-container">
                <select class="form-control dropdown-trigger" id="jenis" name="jenis" required>
                    <option value="krim">Krim</option>
                    <option value="salep">Salep</option>
                    <option value="sirup">Sirup</option>
                    <option value="gel">Gel</option>
                    <option value="lotion">Lotion</option>
                    <option value="tablet">Tablet</option>
                    <option value="sachet">Sachet</option>
                    <option value="pil">Pil</option>
                    <option value="kapsul">Kapsul</option>
                    <option value="kaplet">Kaplet</option>
                    <option value="bubuk">Bubuk</option>
                    <option value="oles">Oles</option>
                    <option value="spray">Spray</option>
                    <option value="tetes">Tetes</option>
                </select>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </div>
        </div>


        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga Obat</label>
            <input type="number" class="form-control" id="harga" name="harga" step="0.01" required>
        </div>



        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
