<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\PrioritasController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;


// Halaman welcome / public
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Middleware
Route::middleware('isLogin')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
});

    Route::middleware('checkLogin')->group(function () {
        Route::get('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource 
    Route::resource('data', DataController::class);

    // Cluster
    Route::get('cluster', [ClusterController::class, 'index'])->name('cluster');
    Route::post('cluster/proses', [ClusterController::class, 'prosesCluster'])->name('cluster.proses');
    Route::get('cluster/reset', [ClusterController::class, 'resetCluster'])->name('cluster.reset');

    // Prioritas
    Route::get('prioritas', [PrioritasController::class, 'index'])->name('prioritas');
    Route::post('prioritas/proses', [PrioritasController::class, 'prosesTopsis'])->name('prioritas.proses');
    Route::get('/prioritas/reset', [App\Http\Controllers\PrioritasController::class, 'resetTopsis'])->name('prioritas.reset');
    Route::get('/prioritas/cetak', [App\Http\Controllers\PrioritasController::class, 'cetakLaporan'])->name('prioritas.cetak');

    // Peta
    Route::get('peta', [PetaController::class, 'index'])->name('peta');
});

// Landing Page Route
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('welcome');

// Catatan: Route 'check-sync-token' tidak dimasukkan ke middleware auth
// karena diasumsikan bisa diakses kapan saja untuk polling. Jika butuh login, pindahkan ke dalam.
Route::get('/check-sync-token', function (\Illuminate\Http\Request $request) {
    $token = $request->cookie('sync_token');
    $valid = $token && \App\Models\UserSession::where('session_token', $token)->exists();
    return response()->json(['valid' => $valid]);
});