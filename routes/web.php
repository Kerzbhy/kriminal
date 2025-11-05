<?php

use App\Http\Controllers\ClusterController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\PrioritasController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('isLogin')->group(function () {
    //login
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginProses'])->name('loginProses');

});



//logout
Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware('checkLogin')->group(function () {
    //dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //data
    Route::get('data', [DataController::class, 'index'])->name('data');
    Route::resource('data', DataController::class);

    //cluster
    Route::get('cluster', [ClusterController::class, 'index'])->name('cluster');

    //prioritas
    Route::get('prioritas', [PrioritasController::class, 'index'])->name('prioritas');

    //peta
    Route::get('peta', [PetaController::class, 'index'])->name('peta');
});
