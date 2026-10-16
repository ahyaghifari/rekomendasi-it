<?php

use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\PicSessionController;
use App\Http\Controllers\ProjectTaskController;
use App\Http\Controllers\SuratRekomendasiController;
use Illuminate\Support\Facades\Route;

// ─────────────────────────────────────────────────────────────────────────────
//  Halaman utama — Single Page Monitoring Dashboard (publik, read-only).
//  Tambah/Ubah/Hapus data dilakukan melalui Admin Panel Filament.
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/', [MonitoringController::class, 'index'])->name('monitoring');

// ─────────────────────────────────────────────────────────────────────────────
//  Akun PIC: login dari beranda, lalu kelola tugas project miliknya (JSON/fetch).
// ─────────────────────────────────────────────────────────────────────────────
Route::post('/masuk', [PicSessionController::class, 'store'])->middleware('throttle:6,1')->name('pic.login');

Route::middleware('auth')->group(function () {
    Route::post('/keluar', [PicSessionController::class, 'destroy'])->name('pic.logout');
    Route::post('/projects/{project}/tasks', [ProjectTaskController::class, 'store'])->name('tasks.store');
    Route::patch('/tasks/{checklist}', [ProjectTaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{checklist}', [ProjectTaskController::class, 'destroy'])->name('tasks.destroy');
});

// ─────────────────────────────────────────────────────────────────────────────
//  Surat Rekomendasi IT (publik): daftar, buat baru, dan unduh PDF.
//  Ubah/hapus surat hanya lewat panel Filament /surat_rekomendasi.
// ─────────────────────────────────────────────────────────────────────────────
Route::get('/surat-rekomendasi', [SuratRekomendasiController::class, 'index'])->name('surat.index');
Route::get('/surat-rekomendasi/buat', [SuratRekomendasiController::class, 'create'])->name('surat.create');
Route::post('/surat-rekomendasi', [SuratRekomendasiController::class, 'store'])->middleware('throttle:10,1')->name('surat.store');
Route::get('/surat-rekomendasi/{surat}/pdf', [SuratRekomendasiController::class, 'downloadPdf'])->name('surat.pdf');
Route::get('/api/perusahaan/{perusahaan}/departemen', [SuratRekomendasiController::class, 'getDepartemenByPerusahaan'])->name('surat.departemen');
