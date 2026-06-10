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
    UserController,
    ActivityLogController
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

    // 2. Master Data (Protected by individual resource permissions)
    Route::middleware(['can:manage_gunung'])->group(function () {
        Route::resource('gunung', GunungController::class)->names([
            'index'   => 'admin.gunung.index',
            'create'  => 'gunung.create',
            'store'   => 'gunung.store',
            'edit'    => 'gunung.edit',
            'update'  => 'gunung.update',
            'destroy' => 'gunung.destroy',
        ]);
        Route::delete('gunung/{id}/delete-image', [GunungController::class, 'deleteImage'])->name('gunung.delete-image');
    });

    Route::middleware(['can:manage_terminal'])->group(function () {
        Route::resource('terminal', TerminalController::class)->names([
            'index'   => 'admin.terminal.index',
            'store'   => 'terminal.store',
            'update'  => 'terminal.update',
            'destroy' => 'terminal.destroy',
        ]);
    });

    Route::middleware(['can:manage_jalur'])->group(function () {
        Route::resource('jalur', JalurController::class)->names([
            'index'   => 'admin.jalur.index',
            'store'   => 'jalur.store',
            'destroy' => 'jalur.destroy',
        ]);
    });

    Route::middleware(['can:manage_biaya'])->group(function () {
        Route::resource('biaya', BiayaController::class)->names([
            'index'   => 'admin.biaya.index',
            'store'   => 'biaya.store',
            'destroy' => 'biaya.destroy',
        ]);
        Route::post('biaya/apply-period', [BiayaController::class, 'applyPeriod'])->name('biaya.apply_period');
        Route::post('biaya/reset-period/{id}', [BiayaController::class, 'resetPeriod'])->name('biaya.reset_period');
    });

    // 3. Metode MOORA (Protected by individual MOORA permissions)
    Route::middleware(['can:manage_kriteria'])->group(function () {
        Route::resource('kriteria', KriteriaController::class)->names([
            'index' => 'admin.kriteria.index',
        ]);
    });

    Route::middleware(['can:manage_sub_kriteria'])->group(function () {
        Route::resource('sub-kriteria', SubKriteriaController::class)->names([
            'index'   => 'admin.sub-kriteria.index',
            'store'   => 'sub-kriteria.store',
            'update'  => 'sub-kriteria.update',
            'destroy' => 'sub-kriteria.destroy',
        ]);
    });

    Route::middleware(['can:manage_penilaian'])->group(function () {
        Route::get('/penilaian', [PenilaianController::class, 'index'])->name('admin.penilaian.index');
        // Let's keep store and destroy endpoints protected under manage_penilaian
        Route::post('/penilaian', [PenilaianController::class, 'store'])->name('admin.penilaian.store');
        Route::delete('/penilaian/destroy/{jalur}/{biaya}', [PenilaianController::class, 'destroy'])->name('admin.penilaian.destroy');
    });

    Route::middleware(['can:view_hasil'])->group(function () {
        Route::get('/hasil', [HasilController::class, 'index'])->name('hasil.perhitungan');
    });

    // 8. Kelola User
    Route::resource('users', UserController::class)->middleware('can:manage_users')->names([
        'index'   => 'users.index',
        'create'  => 'users.create',
        'store'   => 'users.store',
        'edit'    => 'users.edit',
        'update'  => 'users.update',
        'destroy' => 'users.destroy',
    ]);
    Route::post('/users/permissions', [UserController::class, 'updatePermissions'])->name('admin.users.update_permissions')->middleware('can:manage_users');

    // 9. Log Aktivitas
    Route::get('/logs', [ActivityLogController::class, 'index'])->name('admin.logs.index')->middleware('can:view_logs');
    Route::get('/logs/export', [ActivityLogController::class, 'export'])->name('admin.logs.export')->middleware('can:view_logs');
    Route::post('/logs/clear', [ActivityLogController::class, 'clear'])->name('admin.logs.clear')->middleware('can:view_logs');

});