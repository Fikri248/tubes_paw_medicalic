<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ObatController;

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

Route::get('/master-data/jenis', function () {
    return view('master_data.jenis');
})->name('master-data.jenis');

Route::get('/master-data/kategori', [KategoriController::class, 'index'])->name('master-data.kategori');
Route::get('/master-data/kategori/data', [KategoriController::class, 'getData'])->name('master-data.kategori.data');

Route::delete('/master-data/kategori/{id}', [KategoriController::class, 'destroy'])->name('master-data.kategori.destroy');

Route::get('/master-data/kategori/create', [KategoriController::class, 'create'])->name('master-data.kategori.create');
Route::post('/master-data/kategori', [KategoriController::class, 'store'])->name('master-data.kategori.store');


Route::get('/master-data/obat', [ObatController::class, 'index'])->name('master-data.obat');
Route::get('/master-data/obat/create', [ObatController::class, 'create'])->name('master-data.obat.create');
Route::post('/master-data/obat', [ObatController::class, 'store'])->name('master-data.obat.store');
Route::get('/master-data/obat/{id}/edit', [ObatController::class, 'edit'])->name('master-data.obat.edit');
Route::put('/master-data/obat/{id}', [ObatController::class, 'update'])->name('master-data.obat.update');

Route::delete('/master-data/obat/{id}', [ObatController::class, 'destroy'])->name('master-data.obat.destroy');




Route::get('/transaksi', function () {
    return view('transaksi.index');
})->name('transaksi');

Route::get('/laporan', function () {
    return view('laporan.index');
})->name('laporan');
