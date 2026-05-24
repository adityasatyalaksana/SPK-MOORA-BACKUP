<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER PUBLIK / PENDAKI ---
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\AuthController;

// --- CONTROLLER ADMIN (Dikelompokkan) ---
use App\Http\Controllers\Admin\{
    DashboardController,
    GunungController,
    TerminalController,
    JalurController,
    BiayaController,
    KriteriaController,
    SubKriteriaController,
    PenilaianController,
    HasilController,
    UserController
};

/*
|--------------------------------------------------------------------------
| RUTE PUBLIK (User/Pendaki Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [BerandaController::class, 'index'])->name('beranda.index');

// Halaman Profil Gunung
Route::get('/profile-gunung', [ProfileController::class, 'index'])->name('pendaki.profile.index');

/** * FITUR CARI REKOMENDASI
 * Menggunakan RekomendasiController untuk logika MOORA & Budgeting
 */
Route::get('/cari-rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
Route::get('/pilihan-gunung', [RekomendasiController::class, 'proses'])->name('rekomendasi.proses');

/*
|--------------------------------------------------------------------------
| RUTE AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| AREA ADMIN (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('admin')->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // 2. Master Data: Gunung
    Route::resource('gunung', GunungController::class)->names([
        'index'   => 'admin.gunung.index',
        'create'  => 'gunung.create',
        'store'   => 'gunung.store',
        'edit'    => 'gunung.edit',
        'update'  => 'gunung.update',
        'destroy' => 'gunung.destroy',
    ]);
    Route::delete('gunung/{id}/delete-image', [GunungController::class, 'deleteImage'])->name('gunung.delete-image');

    // 3. Master Data: Terminal
    Route::resource('terminal', TerminalController::class)->names([
        'index'   => 'admin.terminal.index',
        'store'   => 'terminal.store',
        'update'  => 'terminal.update',
        'destroy' => 'terminal.destroy',
    ]);

    // 4. Master Data: Jalur
    Route::resource('jalur', JalurController::class)->names([
        'index'   => 'admin.jalur.index',
        'store'   => 'jalur.store',
        'destroy' => 'jalur.destroy',
    ]);

    // 5. Master Data: Biaya
    Route::resource('biaya', BiayaController::class)->names([
        'index'   => 'admin.biaya.index',
        'store'   => 'biaya.store',
        'destroy' => 'biaya.destroy',
    ]);
    Route::post('biaya/apply-period', [BiayaController::class, 'applyPeriod'])->name('biaya.apply_period');
    Route::post('biaya/reset-period/{id}', [BiayaController::class, 'resetPeriod'])->name('biaya.reset_period'); // <-- SINKRONISASI ROUTE BARU

    // 6. Master Data: Kriteria & Sub-Kriteria
    Route::resource('kriteria', KriteriaController::class)->names([
        'index' => 'admin.kriteria.index',
    ]);

    Route::resource('sub-kriteria', SubKriteriaController::class)->names([
        'index'   => 'admin.sub-kriteria.index',
        'store'   => 'sub-kriteria.store',
        'update'  => 'sub-kriteria.update',
        'destroy' => 'sub-kriteria.destroy',
    ]);

    // 7. Metode MOORA: Penilaian & Hasil
    Route::get('/penilaian', [PenilaianController::class, 'index'])->name('admin.penilaian.index');
    Route::post('/penilaian', [PenilaianController::class, 'store'])->name('admin.penilaian.store');
    Route::delete('/penilaian/destroy/{jalur}/{biaya}', [PenilaianController::class, 'destroy'])->name('admin.penilaian.destroy');
    
    Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.perhitungan');

    // 8. Kelola User
    Route::resource('users', UserController::class)->names([
        'index'   => 'users.index',
        'create'  => 'users.create',
        'store'   => 'users.store',
        'edit'    => 'users.edit',
        'update'  => 'users.update',
        'destroy' => 'users.destroy',
    ]);

});