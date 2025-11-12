<?php

use App\Http\Controllers\ClusterController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\PrioritasController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Halaman welcome / public
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Middleware untuk mencegah user yang sudah login membuka login lagi
Route::middleware('isLogin')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');
});

// Logout sinkron
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk cek sync_token (JS polling)
Route::get('/check-sync-token', function (\Illuminate\Http\Request $request) {
    $token = $request->cookie('sync_token');
    $valid = $token && \App\Models\UserSession::where('session_token', $token)->exists();
    return response()->json(['valid' => $valid]);
});

// Middleware untuk halaman yang butuh login
Route::middleware(['checkLogin', 'web'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Data
    Route::get('data', [DataController::class, 'index'])->name('data');
    Route::resource('data', DataController::class);

    // Cluster
    Route::get('cluster', [ClusterController::class, 'index'])->name('cluster');

    // Prioritas
    Route::get('prioritas', [PrioritasController::class, 'index'])->name('prioritas');

    // Peta
    Route::get('peta', [PetaController::class, 'index'])->name('peta');
});
