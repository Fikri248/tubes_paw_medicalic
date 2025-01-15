@extends('layouts.app')

@section('title', 'Tambah Obat')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary fw-semibold">
                        <i class="fas fa-pills me-2"></i>Tambah Obat Baru
                    </h5>
                    <a href="{{ route('master-data.obat') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('master-data.obat.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama" name="nama" value="{{ old('nama') }}" placeholder="Nama Obat"
                                    required>
                                <label for="nama">Nama Obat</label>
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating dropdown-container">
                                <select class="form-select dropdown-with-icon @error('category_id') is-invalid @enderror"
                                    id="category_id" name="category_id" required>
                                    <option value="" disabled selected>Pilih kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                                <label for="category_id">Kategori</label>
                                @error('category_id')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating dropdown-container">
                                <select class="form-select dropdown-with-icon @error('jenis') is-invalid @enderror"
                                    id="jenis" name="jenis" required>
                                    <option value="" disabled selected>Pilih jenis</option>
                                    @foreach (['krim', 'salep', 'sirup', 'gel', 'lotion', 'tablet', 'sachet', 'pil', 'kapsul', 'kaplet', 'bubuk', 'oles', 'spray', 'tetes'] as $jenis)
                                        <option value="{{ $jenis }}" {{ old('jenis') == $jenis ? 'selected' : '' }}>
                                            {{ ucfirst($jenis) }}</option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                                <label for="jenis">Jenis Obat</label>
                                @error('jenis')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                    id="stok" name="stok" value="{{ old('stok') }}" min="0"
                                    placeholder="Stok" required>
                                <label for="stok">Stok Awal</label>
                                @error('stok')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control @error('harga') is-invalid @enderror"
                                    id="harga" name="harga" value="{{ old('harga') }}" step="0.01"
                                    placeholder="Harga" required>
                                <label for="harga">Harga Obat</label>
                                @error('harga')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi"
                                    style="height: 100px" placeholder="Deskripsi" required>{{ old('deskripsi') }}</textarea>
                                <label for="deskripsi">Deskripsi</label>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save me-2"></i>Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
            btn.disabled = true;
        });
    </script>
@endsection
