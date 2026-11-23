<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MedicineReportController;
use App\Http\Controllers\ObatAnakController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('obat-anak.index');
});

Route::get('obat-anak/laporan', [MedicineReportController::class, 'index'])->name('obat-anak.laporan');
Route::get('obat-anak/laporan/export', [MedicineReportController::class, 'export'])->name('obat-anak.laporan.export');
Route::get('obat-anak/kedaluwarsa', [ObatAnakController::class, 'kedaluwarsa'])->name('obat-anak.kedaluwarsa');
Route::get('stok-obat', [StockController::class, 'index'])->name('stok-obat.index');
Route::post('obat-anak/{obatAnak}/stok', [StockController::class, 'update'])->name('obat-anak.stok.update');
Route::resource('kategori-obat', CategoryController::class)->parameters([
    'kategori-obat' => 'kategoriObat',
])->except(['create', 'show', 'edit']);
Route::get('pengaturan', [SettingsController::class, 'index'])->name('pengaturan.index');
Route::put('pengaturan', [SettingsController::class, 'update'])->name('pengaturan.update');
Route::resource('obat-anak', ObatAnakController::class)->parameters([
    'obat-anak' => 'obatAnak',
]);
