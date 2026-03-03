<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\TrackingLocationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/autoLogin', 'autoLogin');
    Route::post('/logout', 'logout');
    Route::post('/updateFaceId', 'updateFaceId');
    Route::post('/updatePassword', 'updatePassword');
});


Route::prefix('tracking')->controller(TrackingLocationController::class)->group(function () {
    Route::post('/sendLocation', 'sendLocation');
    Route::post('/stopLocation', 'stopLocation');
});


Route::prefix('presensi')->controller(PresensiController::class)->group(function () {
    Route::post('/presensi', 'presensi');
    Route::post('/izin', 'izin');
    Route::get('/getPresensi', 'getPresensi');
    Route::get('/getPresensiToday', 'getPresensiToday');
    Route::get('/getDetailPresensi', 'getDetailPresensi');
});
