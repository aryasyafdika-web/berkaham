<?php

use App\Http\Controllers\ObatAnakController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('obat-anak.index');
});

Route::resource('obat-anak', ObatAnakController::class)->parameters([
    'obat-anak' => 'obatAnak',
]);
