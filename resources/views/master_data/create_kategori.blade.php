@extends('layouts.app')

@section('title', $pageTitle)
@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">{{ $pageTitle }}</h1>

        <form action="{{ route('master-data.kategori.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nama Kategori</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}" placeholder="Masukkan nama kategori">
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi-save me-2"></i> Simpan
            </button>
            <a href="{{ route('master-data.kategori') }}" class="btn btn-secondary">
                <i class="bi-arrow-left-circle me-2"></i> Kembali
            </a>
        </form>
    </div>
@endsection
