@extends('layouts.app')

@section('title', 'Edit Obat')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white py-4 border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-primary fw-semibold">
                        <i class="fas fa-edit me-2"></i>Edit Obat
                    </h5>
                    <a href="{{ route('master-data.obat') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('master-data.obat.update', $obat->id) }}" method="POST" id="updateForm">
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="nama" name="nama"
                                    value="{{ $obat->nama }}" placeholder="Nama Obat" required>
                                <label for="nama"><i class="fas fa-prescription-bottle me-2"></i>Nama Obat</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating dropdown-container">
                                <select class="form-select dropdown-with-icon" id="category_id" name="category_id" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ $obat->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                                <label for="category_id"><i class="fas fa-tags me-2"></i>Kategori</label>
                            </div>

                        </div>

                        <div class="col-md-4">
                            <div class="form-floating dropdown-container">
                                <select class="form-select dropdown-with-icon" id="jenis" name="jenis" required>
                                    @foreach (['krim', 'salep', 'sirup', 'gel', 'lotion', 'tablet', 'sachet', 'pil', 'kapsul', 'kaplet', 'bubuk', 'oles', 'spray', 'tetes'] as $jenis)
                                        <option value="{{ $jenis }}" {{ $obat->jenis == $jenis ? 'selected' : '' }}>
                                            {{ ucfirst($jenis) }}
                                        </option>
                                    @endforeach
                                </select>
                                <i class="fas fa-chevron-down dropdown-icon"></i>
                                <label for="jenis"><i class="fas fa-pills me-2"></i>Jenis Obat</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control bg-light" id="stok_awal"
                                    value="{{ $obat->stok_awal }}" placeholder="Stok Awal" readonly>
                                <label for="stok_awal"><i class="fas fa-box me-2"></i>Stok Awal</label>
                            </div>
                            <div class="form-floating">
                                <input type="number" class="form-control" id="stok_sisa" name="stok_sisa"
                                    value="{{ $stokSisa }}" placeholder="Stok Sisa" required>
                                <label for="stok_sisa"><i class="fas fa-box-open me-2"></i>Stok Sisa</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="harga" name="harga"
                                    value="{{ old('harga', $obat->harga) }}" step="1" placeholder="Harga" required>
                                <label for="harga"><i class="fas fa-tag me-2"></i>Harga Obat</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="deskripsi" name="deskripsi" style="height: 100px" placeholder="Deskripsi" required>{{ $obat->deskripsi }}</textarea>
                                <label for="deskripsi"><i class="fas fa-align-left me-2"></i>Deskripsi</label>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.getElementById('updateForm').addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Perubahan',
                    text: "Apakah Anda yakin ingin menyimpan perubahan?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3498db',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const btn = this.querySelector('button[type="submit"]');
                        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                        btn.disabled = true;
                        this.submit();
                    }
                });
            });
        </script>
    @endpush
@endsection
