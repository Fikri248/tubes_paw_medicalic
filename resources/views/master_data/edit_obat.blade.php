@extends('layouts.app')

@section('title', 'Edit Obat')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Edit Obat</h1>
        <a href="{{ route('master-data.obat') }}" class="btn btn-secondary">
            <i class="bi-arrow-left-circle me-2"></i> Kembali ke Daftar Obat
        </a>
    </div>

    <form action="{{ route('master-data.obat.update', $obat->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="nama" class="form-label">Nama Obat</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ $obat->nama }}" required>
        </div>

        <div class="mb-3">
            <label for="category_id" class="form-label">Kategori</label>
            <div class="dropdown-container">
                <select class="form-control dropdown-trigger" id="category_id" name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $obat->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </div>
        </div>

        <div class="mb-3">
            <label for="jenis" class="form-label">Jenis Obat</label>
            <div class="dropdown-container">
                <select class="form-control dropdown-trigger" id="jenis" name="jenis" required>
                    <option value="krim" {{ $obat->jenis == 'krim' ? 'selected' : '' }}>Krim</option>
                    <option value="salep" {{ $obat->jenis == 'salep' ? 'selected' : '' }}>Salep</option>
                    <option value="sirup" {{ $obat->jenis == 'sirup' ? 'selected' : '' }}>Sirup</option>
                    <option value="gel" {{ $obat->jenis == 'gel' ? 'selected' : '' }}>Gel</option>
                    <option value="lotion" {{ $obat->jenis == 'lotion' ? 'selected' : '' }}>Lotion</option>
                    <option value="tablet" {{ $obat->jenis == 'tablet' ? 'selected' : '' }}>Tablet</option>
                    <option value="sachet" {{ $obat->jenis == 'sachet' ? 'selected' : '' }}>Sachet</option>
                    <option value="pil" {{ $obat->jenis == 'pil' ? 'selected' : '' }}>Pil</option>
                    <option value="kapsul" {{ $obat->jenis == 'kapsul' ? 'selected' : '' }}>Kapsul</option>
                    <option value="kaplet" {{ $obat->jenis == 'kaplet' ? 'selected' : '' }}>Kaplet</option>
                    <option value="bubuk" {{ $obat->jenis == 'bubuk' ? 'selected' : '' }}>Bubuk</option>
                    <option value="oles" {{ $obat->jenis == 'oles' ? 'selected' : '' }}>Oles</option>
                    <option value="spray" {{ $obat->jenis == 'spray' ? 'selected' : '' }}>Spray</option>
                    <option value="tetes" {{ $obat->jenis == 'tetes' ? 'selected' : '' }}>Tetes</option>
                </select>
                <i class="fas fa-chevron-down dropdown-icon"></i>
            </div>
        </div>

        <div class="mb-3">
            <label for="stok" class="form-label">Stok</label>
            <input type="number" class="form-control" id="stok" name="stok" value="{{ $obat->stok }}" required>
        </div>

        <div class="mb-3">
            <label for="harga" class="form-label">Harga Obat</label>
            <input type="number" class="form-control" id="harga" name="harga" value="{{ old('harga', $obat->harga) }}" step="0.01" required>
        </div>



        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $obat->deskripsi }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
@endsection
