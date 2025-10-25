<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\PeminjamanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', '2fa', 'verified'])
    ->name('dashboard');

// Routes 2FA
Route::middleware(['auth'])->group(function () {
    Route::get('/2fa/setup', [TwoFactorController::class, 'show2FASetup'])->name('2fa.setup');
    Route::post('/2fa/setup', [TwoFactorController::class, 'verify2FASetup'])->name('2fa.setup.verify');
    Route::get('/2fa/verify', [TwoFactorController::class, 'show2FAVerify'])->name('2fa.verify');
    Route::post('/2fa/verify', [TwoFactorController::class, 'verify2FA'])->name('2fa.verify.post');
    Route::post('/2fa/disable', [TwoFactorController::class, 'disable2FA'])->name('2fa.disable');
});

// Routes Laporan - CRUD Lengkap
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/create', [LaporanController::class, 'create'])->name('laporan.create');
    Route::post('/laporan', [LaporanController::class, 'store'])->name('laporan.store');
    Route::get('/laporan/{id}', [LaporanController::class, 'show'])->name('laporan.show');
    Route::get('/laporan/{id}/edit', [LaporanController::class, 'edit'])->name('laporan.edit');
    Route::put('/laporan/{id}', [LaporanController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy'])->name('laporan.destroy');

    // Export Routes
    Route::get('/laporan/export/form', [LaporanController::class, 'showExportForm'])->name('laporan.export.form');
    Route::get('/laporan/export/csv', [LaporanController::class, 'exportCSV'])->name('laporan.export.csv');
    Route::get('/laporan/export/excel', [LaporanController::class, 'exportExcel'])->name('laporan.export.excel');

    // Statistik API Routes
    Route::get('/laporan/statistik/{jenis}', [LaporanController::class, 'getStatisticsBySatuan'])->name('laporan.statistik');
    Route::get('/laporan/statistik/{jenis}/{satuan}', [LaporanController::class, 'getStatisticsBySatuan'])->name('laporan.statistik.satuan');
});

// Routes Peminjaman
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
    Route::get('/peminjaman/{id}/edit', [PeminjamanController::class, 'edit'])->name('peminjaman.edit');
    Route::put('/peminjaman/{id}', [PeminjamanController::class, 'update'])->name('peminjaman.update');
    Route::delete('/peminjaman/{id}', [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');
    Route::post('/peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{id}/return', [PeminjamanController::class, 'return'])->name('peminjaman.return');
});

// Routes Barcode
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/barcode', [BarcodeController::class, 'index'])->name('barcode.index');
    Route::post('/barcode/generate', [BarcodeController::class, 'generate'])->name('barcode.generate');
    Route::get('/barcode/print/{kode_barang}/{jenis}', [BarcodeController::class, 'printLabel'])->name('barcode.print');
});

// Route untuk scan barcode (bisa diakses tanpa auth)
Route::get('/scan/{kode_barang}', [BarcodeController::class, 'scan'])->name('barcode.scan');

// Routes Notifications
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/clear', [NotificationController::class, 'clearAll'])->name('notifications.clear');
});

// Routes Statistik
Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/statistik', [StatistikController::class, 'index'])->name('statistik.index');
    Route::get('/statistik/export/pdf', [StatistikController::class, 'exportPDF'])->name('statistik.export.pdf');
    Route::get('/statistik/export/csv', [StatistikController::class, 'exportStatistikCSV'])->name('statistik.export.csv');
    Route::get('/statistik/api', [StatistikController::class, 'apiData'])->name('statistik.api');
});

Route::middleware(['auth', '2fa'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
