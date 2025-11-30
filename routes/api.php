<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MobileController;

// Ini route default Laravel 11 (biarkan atau hapus, bebas)
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// === TAMBAHKAN ROUTE API UNTUK FLUTTER DISINI ===
Route::get('/map-data', [MobileController::class, 'getMapData']);
Route::get('/ranking', [MobileController::class, 'getRanking']);
Route::get('/ranking-detail/{kecamatan}', [App\Http\Controllers\Api\MobileController::class, 'getRankingDetail']);
