@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <h6>Total Obat Tersedia</h6> <!-- Mengganti judul -->
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-download fa-2x"></i>
                    <h3>{{ $totalObatTersedia ?? 0 }}</h3> <!-- Menampilkan total stok sisa -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <h6>Total Obat Terjual</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-upload fa-2x"></i>
                    <h3>{{ $totalObatTerjual ?? 0 }}</h3> <!-- Menampilkan jumlah obat terjual -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card orange-card">
                <h6>Total Semua Obat</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-box fa-2x"></i>
                    <h3>{{ $totalSemuaObat ?? 0 }}</h3> <!-- Menampilkan total jenis obat -->
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card purple-card">
                <h6>Total Pendapatan + PPN</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-money-bill fa-2x"></i>
                    <h3>Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
                    <!-- Menampilkan total pendapatan -->
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Selamat Datang di Website Apotek Medicalic</h5>
            <p class="card-text text-muted">Anda adalah anggota kelompok 2 yang memiliki akses penuh terhadap sistem.</p>
        </div>
    </div>
@endsection
