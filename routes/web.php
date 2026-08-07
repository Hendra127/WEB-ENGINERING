<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SparepartController;
use App\Http\Controllers\AlatKantorController;
use App\Http\Controllers\PeminjamanAlatController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\PengajuanPerangkatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;

Route::get('/', fn() => redirect()->route('engineering.dashboard'));

Route::middleware('auth')->prefix('engineering')->name('engineering.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/sparepart',             [SparepartController::class, 'index'])->name('sparepart');
    Route::post('/sparepart',            [SparepartController::class, 'store'])->name('sparepart.store');
    Route::post('/sparepart/import',     [SparepartController::class, 'import'])->name('sparepart.import');
    Route::get('/sparepart/template',    [SparepartController::class, 'template'])->name('sparepart.template');
    Route::put('/sparepart/{item}',      [SparepartController::class, 'update'])->name('sparepart.update');
    Route::delete('/sparepart/{item}',   [SparepartController::class, 'destroy'])->name('sparepart.destroy');
    Route::get('/sparepart/{item}/print-ba', [SparepartController::class, 'printBA'])->name('sparepart.print_ba');

    Route::get('/alat-kantor',           [AlatKantorController::class, 'index'])->name('alat');
    Route::post('/alat-kantor',          [AlatKantorController::class, 'store'])->name('alat.store');
    Route::put('/alat-kantor/{item}',    [AlatKantorController::class, 'update'])->name('alat.update');
    Route::delete('/alat-kantor/{item}', [AlatKantorController::class, 'destroy'])->name('alat.destroy');

    Route::get('/peminjaman-alat',             [PeminjamanAlatController::class, 'index'])->name('peminjaman');
    Route::post('/peminjaman-alat',            [PeminjamanAlatController::class, 'store'])->name('peminjaman.store');
    Route::put('/peminjaman-alat/{item}',      [PeminjamanAlatController::class, 'update'])->name('peminjaman.update');
    Route::delete('/peminjaman-alat/{item}',   [PeminjamanAlatController::class, 'destroy'])->name('peminjaman.destroy');

    Route::get('/klasifikasi',             [KlasifikasiController::class, 'index'])->name('klasifikasi');
    Route::post('/klasifikasi',            [KlasifikasiController::class, 'store'])->name('klasifikasi.store');
    Route::put('/klasifikasi/{item}',      [KlasifikasiController::class, 'update'])->name('klasifikasi.update');
    Route::delete('/klasifikasi/{item}',   [KlasifikasiController::class, 'destroy'])->name('klasifikasi.destroy');

    Route::get('/pengajuan-perangkat',             [PengajuanPerangkatController::class, 'index'])->name('pengajuan_perangkat');
    Route::post('/pengajuan-perangkat',            [PengajuanPerangkatController::class, 'store'])->name('pengajuan_perangkat.store');
    Route::post('/pengajuan-perangkat/{item}/approve', [PengajuanPerangkatController::class, 'approve'])->name('pengajuan_perangkat.approve');
    Route::post('/pengajuan-perangkat/{item}/reject',  [PengajuanPerangkatController::class, 'reject'])->name('pengajuan_perangkat.reject');
    Route::delete('/pengajuan-perangkat/{item}',   [PengajuanPerangkatController::class, 'destroy'])->name('pengajuan_perangkat.destroy');
    Route::get('/pengajuan-perangkat/{item}/print', [PengajuanPerangkatController::class, 'print'])->name('pengajuan_perangkat.print');

    Route::get('/profile',  [ProfileController::class, 'profile'])->name('profile');
    Route::put('/profile',  [ProfileController::class, 'profileUpdate'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'passwordUpdate'])->name('profile.password');
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
