<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportPdfObatController;
use App\Http\Controllers\ExportPdfTransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ObatController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/master-data/jenis', fn() => view('master_data.jenis'))->name('master-data.jenis');

// kategori
Route::get('/master-data/kategori', [KategoriController::class, 'index'])->name('master-data.kategori');
Route::get('/master-data/kategori/data', [KategoriController::class, 'getData'])->name('master-data.kategori.data');

Route::delete('/master-data/kategori/{id}', [KategoriController::class, 'destroy'])->name('master-data.kategori.destroy');

Route::get('/master-data/kategori/create', [KategoriController::class, 'create'])->name('master-data.kategori.create');
Route::post('/master-data/kategori', [KategoriController::class, 'store'])->name('master-data.kategori.store');

// obat
Route::get('/master-data/obat', [ObatController::class, 'index'])->name('master-data.obat');
Route::get('/master-data/obat/create', [ObatController::class, 'create'])->name('master-data.obat.create');
Route::post('/master-data/obat', [ObatController::class, 'store'])->name('master-data.obat.store');
Route::get('/master-data/obat/{id}/edit', [ObatController::class, 'edit'])->name('master-data.obat.edit');
Route::put('/master-data/obat/{id}', [ObatController::class, 'update'])->name('master-data.obat.update');
Route::get('/master-data/obat/pdf', [ExportPdfObatController::class, 'exportPdf'])->name('master-data.obat.pdf');
// Route::get('/master-data/obat/preview/pdf', [ExportPdfObatController::class, 'index'])->name('master-data.obat.pdf.preview');

Route::delete('/master-data/obat/{id}', [ObatController::class, 'destroy'])->name('master-data.obat.destroy');

// transaksi
Route::group(['prefix' => 'transaksi', 'as' => 'transaksi.'], function () {
    Route::get('/', [TransaksiController::class, 'index'])->name('obat.index');
    Route::get('/create', [TransaksiController::class, 'create'])->name('obat.create');
    Route::get('/{obat}/selected', [TransaksiController::class, 'getSelectedObat']);
    Route::post('/store', [TransaksiController::class, 'store'])->name('obat.store');
    Route::get('/edit/{transaksi}', [TransaksiController::class, 'edit'])->name('obat.edit');
    Route::put('/update/{transaksi}', [TransaksiController::class, 'update'])->name('obat.update');
    Route::delete('/delete/{transaksi}', [TransaksiController::class, 'destroy'])->name('obat.destroy');
    Route::get('/pdf', [ExportPdfTransaksiController::class, 'exportPdf'])->name('obat.pdf');
    // Route::get('/preview/pdf', [ExportPdfTransaksiController::class, 'index'])->name('obat.pdf.preview');
});

Route::get('/laporan', fn() =>  view('laporan.index'))->name('laporan');
