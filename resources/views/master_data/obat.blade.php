@extends('layouts.app') <!-- Pastikan ini merujuk ke layout utama Anda -->

@section('title', 'Data Obat')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Daftar Obat</h1>
        <a href="{{ route('master-data.obat.create') }}" class="btn btn-primary">
            <i class="bi-plus-circle me-2"></i> Tambah Obat Baru
        </a>
    </div>

    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Stok</th>
                <th>Harga</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($obats as $obat)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obat->nama }}</td>
                    <td>{{ $obat->category->name }}</td> <!-- Menampilkan nama kategori yang terkait -->
                    <td>{{ $obat->jenis }}</td>
                    <td>{{ $obat->stok }}</td>
                    <td>Rp {{ number_format($obat->harga, 2, ',', '.') }}</td>
                    <td>{{ $obat->deskripsi}}</td>
                    <td>
                        <!-- Tombol Edit -->
                        <a href="{{ route('master-data.obat.edit', $obat->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>


                        <!-- Form untuk Hapus -->
                        <form action="{{ route('master-data.obat.destroy', $obat->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus obat ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>

                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data obat.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
