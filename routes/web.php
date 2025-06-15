<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {
    Route::redirect('/', 'login');
    
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/tambah', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/ubah/{id}', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::get('/arsip/tambah', [ArsipController::class, 'create'])->name('arsip.create');
    Route::post('/arsip', [ArsipController::class, 'store'])->name('arsip.store');
    Route::get('/arsip/detail/{id}', [ArsipController::class, 'show'])->name('arsip.show');
    Route::get('/arsip/ubah/{id}', [ArsipController::class, 'edit'])->name('arsip.edit');
    Route::put('/arsip/{id}', [ArsipController::class, 'update'])->name('arsip.update');
    Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])->name('arsip.destroy');
    Route::get('/arsip/download/{id}', [ArsipController::class, 'download'])->name('arsip.download');
    
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/filter', [LaporanController::class, 'filter'])->name('laporan.filter');
    Route::get('/laporan/print/{kategori}/{tgl_awal}/{tgl_akhir}', [LaporanController::class, 'print'])->name('laporan.print');
    
    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/user', [UserController::class, 'index'])->name('user.index');
        Route::get('/user/tambah', [UserController::class, 'create'])->name('user.create');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');
        Route::get('/user/ubah/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
    });
    
    Route::get('/password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    
 

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});