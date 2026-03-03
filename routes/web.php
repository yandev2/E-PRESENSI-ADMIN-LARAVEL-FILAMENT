<?php

use App\Http\Controllers\ExportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('export/export-karyawan', [ExportController::class, 'export_karyawan'])
    ->name('export.karyawan');

Route::get('export/export-jabatan', [ExportController::class, 'export_jabatan'])
    ->name('export.jabatan');

Route::get('export/export-shift', [ExportController::class, 'export_shift'])
    ->name('export.shift');


Route::get('export/export-presensi', [ExportController::class, 'export_presensi'])
    ->name('export.presensi');
