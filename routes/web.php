<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EngineeringController;
use App\Http\Controllers\AuthController;

Route::get('/', fn() => redirect()->route('engineering.dashboard'));

Route::middleware('auth')->prefix('engineering')->name('engineering.')->group(function () {
    Route::get('/dashboard', [EngineeringController::class, 'dashboard'])->name('dashboard');
    // ... other engineering routes ...
    Route::get('/sparepart',             [EngineeringController::class, 'sparepart'])->name('sparepart');
    Route::post('/sparepart',            [EngineeringController::class, 'sparepartStore'])->name('sparepart.store');
    Route::put('/sparepart/{item}',      [EngineeringController::class, 'sparepartUpdate'])->name('sparepart.update');
    Route::delete('/sparepart/{item}',   [EngineeringController::class, 'sparepartDestroy'])->name('sparepart.destroy');

    Route::get('/alat-kantor',           [EngineeringController::class, 'alatKantor'])->name('alat');
    Route::post('/alat-kantor',          [EngineeringController::class, 'alatStore'])->name('alat.store');
    Route::put('/alat-kantor/{item}',    [EngineeringController::class, 'alatUpdate'])->name('alat.update');
    Route::delete('/alat-kantor/{item}', [EngineeringController::class, 'alatDestroy'])->name('alat.destroy');

    Route::get('/klasifikasi',             [EngineeringController::class, 'klasifikasi'])->name('klasifikasi');
    Route::post('/klasifikasi',            [EngineeringController::class, 'klasifikasiStore'])->name('klasifikasi.store');
    Route::put('/klasifikasi/{item}',      [EngineeringController::class, 'klasifikasiUpdate'])->name('klasifikasi.update');
    Route::delete('/klasifikasi/{item}',   [EngineeringController::class, 'klasifikasiDestroy'])->name('klasifikasi.destroy');

    Route::get('/profile',  [EngineeringController::class, 'profile'])->name('profile');
    Route::put('/profile',  [EngineeringController::class, 'profileUpdate'])->name('profile.update');
    Route::put('/profile/password', [EngineeringController::class, 'passwordUpdate'])->name('profile.password');
    Route::get('/settings', [EngineeringController::class, 'settings'])->name('settings');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
