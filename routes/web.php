<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KpiController;
use App\Http\Controllers\UlpController;
use App\Http\Controllers\RealisasiController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\EvaluasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PenggunaController;

// AUTH
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class,'showLogin'])->name('login');
    Route::post('/login', [AuthController::class,'login'])->name('login.post');
});
Route::post('/logout', [AuthController::class,'logout'])->name('logout')->middleware('auth');

// PROTECTED
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');

    // ── Admin UP3 only ──────────────────────────────────────────
    Route::middleware('role:admin_up3')->group(function () {
        // Kelola Master KPI
        Route::get('/kpi',             [KpiController::class,'index'])->name('kpi.index');
        Route::post('/kpi',            [KpiController::class,'store'])->name('kpi.store');
        Route::put('/kpi/{id}',        [KpiController::class,'update'])->name('kpi.update');
        Route::delete('/kpi/{id}',     [KpiController::class,'destroy'])->name('kpi.destroy');

        // Kelola ULP
        Route::get('/ulp',             [UlpController::class,'index'])->name('ulp.index');
        Route::post('/ulp',            [UlpController::class,'store'])->name('ulp.store');
        Route::put('/ulp/{id}',        [UlpController::class,'update'])->name('ulp.update');
        Route::delete('/ulp/{id}',     [UlpController::class,'destroy'])->name('ulp.destroy');

        // Validasi KPI UP3
        Route::get('/validasi',                [RealisasiController::class,'validasiIndex'])->name('validasi.index');
        Route::patch('/validasi/{id}/approve', [RealisasiController::class,'approve'])->name('validasi.approve');
        Route::patch('/validasi/{id}/reject',  [RealisasiController::class,'reject'])->name('validasi.reject');

        // Kelola Pengguna
        Route::get('/pengguna',          [PenggunaController::class,'index'])->name('pengguna.index');
        Route::post('/pengguna',         [PenggunaController::class,'store'])->name('pengguna.store');
        Route::put('/pengguna/{id}',     [PenggunaController::class,'update'])->name('pengguna.update');
        Route::delete('/pengguna/{id}',  [PenggunaController::class,'destroy'])->name('pengguna.destroy');
        Route::patch('/pengguna/{id}/toggle', [PenggunaController::class,'toggle'])->name('pengguna.toggle');

        // Evaluasi & Laporan lengkap (admin_up3)
        Route::post('/evaluasi/generate', [EvaluasiController::class,'generate'])->name('evaluasi.generate');

        // Laporan — hanya admin_up3
        Route::get('/laporan',       [LaporanController::class,'index'])->name('laporan.index');
        Route::get('/laporan/pdf',   [LaporanController::class,'pdf'])->name('laporan.pdf');
        Route::get('/laporan/excel', [LaporanController::class,'excel'])->name('laporan.excel');
    });

    // ── Admin UP3 + Admin ULP: input KPI (controller handle masing-masing) ──
    Route::middleware('role:admin_up3,admin_ulp')->group(function () {
        Route::get('/input',         [RealisasiController::class,'index'])->name('input.index');
        Route::post('/input',        [RealisasiController::class,'store'])->name('input.store');
        Route::put('/input/{id}',    [RealisasiController::class,'update'])->name('input.update');
        Route::delete('/input/{id}', [RealisasiController::class,'destroy'])->name('input.destroy');
    });

    // ── Semua role (view only) ──────────────────────────────────
    Route::get('/monitoring',      [MonitoringController::class,'index'])->name('monitoring.index');
    Route::get('/monitoring/data', [MonitoringController::class,'data'])->name('monitoring.data');

    Route::get('/evaluasi',                    [EvaluasiController::class,'index'])->name('evaluasi.index');
    Route::get('/evaluasi/{bulan}/{tahun}',    [EvaluasiController::class,'detail'])->name('evaluasi.detail');

    Route::get('/profil',           [PenggunaController::class,'profil'])->name('profil');
    Route::post('/profil/update',   [PenggunaController::class,'updateProfil'])->name('profil.update');
    Route::post('/profil/password', [PenggunaController::class,'gantiPassword'])->name('profil.password');

    Route::get('/ulp/{id}', [UlpController::class,'show'])->name('ulp.show');
});
