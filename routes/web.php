<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

// rute landing page
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/master-data/jenis', function () {
    return view('master_data.jenis');
})->name('master-data.jenis');

//rute kategori obat
Route::get('/master-data/kategori', [KategoriController::class, 'index'])->name('master-data.kategori');
Route::get('/master-data/kategori/data', [KategoriController::class, 'getData'])->name('master-data.kategori.data');
Route::get('/master-data/kategori/create', [KategoriController::class, 'create'])->name('master-data.kategori.create');
Route::post('/master-data/kategori', [KategoriController::class, 'store'])->name('master-data.kategori.store');
Route::delete('/master-data/kategori/{id}', [KategoriController::class, 'destroy'])->name('master-data.kategori.destroy');

//rute data obat
Route::get('/master-data/obat/data', [ObatController::class, 'getData'])->name('master-data.obat.data');
Route::get('/master-data/obat', [ObatController::class, 'index'])->name('master-data.obat');
Route::get('/master-data/obat/create', [ObatController::class, 'create'])->name('master-data.obat.create');
Route::post('/master-data/obat', [ObatController::class, 'store'])->name('master-data.obat.store');
Route::get('/master-data/obat/{id}/edit', [ObatController::class, 'edit'])->name('master-data.obat.edit');
Route::put('/master-data/obat/{id}', [ObatController::class, 'update'])->name('master-data.obat.update');
Route::delete('/master-data/obat/{id}', [ObatController::class, 'destroy'])->name('master-data.obat.destroy');

//rute transaksi
Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi');
Route::get('getTransaksi', [TransaksiController::class, 'getData'])->name('transaksi.getData');
Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
Route::delete('/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

//rute laporan
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/obat/pdf', [LaporanController::class, 'cetakObatPdf'])->name('laporan.obat.pdf');
Route::get('/laporan/transaksi/pdf', [LaporanController::class, 'cetakTransaksiPdf'])->name('laporan.transaksi.pdf');
Route::get('/laporan/obat/excel', [LaporanController::class, 'cetakObatExcel'])->name('laporan.obat.excel');
Route::get('/laporan/transaksi/excel', [LaporanController::class, 'cetakTransaksiExcel'])->name('laporan.transaksi.excel');
