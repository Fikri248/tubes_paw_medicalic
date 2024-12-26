@extends('layouts.app')

@section('title', 'Kategori Obat')
@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Daftar Kategori Obat</h1>
        <a href="{{ route('master-data.kategori.create') }}" class="btn btn-primary">
            <i class="bi-plus-circle me-2"></i> Buat Kategori Baru
        </a>
    </div>

    <table class="table table-bordered table-hover table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Dibuat Pada</th>
                <th>Diubah Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $category->updated_at->format('d-m-Y H:i') }}</td>
                    <td>
                        <form action="{{ route('master-data.kategori.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                <i class="bi-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
