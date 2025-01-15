@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card blue-card">
                <h6>Pendapatan Bersih</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-wallet fa-2x"></i>
                    <h3 style="font-size: 24px;">Rp {{ number_format($pendapatanBersih ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card green-card">
                <h6>PPN</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-percent fa-2x"></i>
                    <h3>Rp {{ number_format($ppn ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card orange-card">
                <h6>Obat Terlaris</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-pills fa-2x"></i>
                    <h3 style="font-size: 21px;">{{ $obatTerlaris ?? 0 }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card purple-card">
                <h6>Jumlah Transaksi</h6>
                <div class="d-flex justify-content-between align-items-center">
                    <i class="fas fa-cash-register fa-2x"></i>
                    <h3>{{ number_format($jumlahTransaksi ?? 0, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-pills me-2"></i>
                        Laporan Daftar Obat
                    </h5>
                    <p class="card-text text-muted mb-3">Cetak laporan daftar semua obat yang tersedia di apotek.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('laporan.obat.pdf') }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-2"></i>
                            Cetak PDF
                        </a>
                        <a href="{{ route('laporan.obat.excel') }}" class="btn btn-success">
                            <i class="fas fa-file-excel me-2"></i>
                            Cetak Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-cash-register me-2"></i>
                        Laporan Transaksi Obat
                    </h5>
                    <p class="card-text text-muted mb-3">Cetak laporan seluruh transaksi penjualan obat.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('laporan.transaksi.pdf') }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf me-2"></i>
                            Cetak PDF
                        </a>
                        <a href="{{ route('laporan.transaksi.excel') }}" class="btn btn-success">
                            <i class="fas fa-file-excel me-2"></i>
                            Cetak Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Laporan Apotek Medicalic</h5>
            <p class="card-text text-muted">Ringkasan laporan keuangan dan transaksi apotek.</p>
        </div>
    </div>
@endsection

@push('styles')
@endpush
