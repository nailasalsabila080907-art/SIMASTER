<?php

use App\Http\Controllers\ApprovalSuratKeluarController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiSuratMasukController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KategoriSuratController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TemplateSuratController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notifikasi}/tandai-dibaca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.tandai-dibaca');
    Route::post('/notifikasi/tandai-semua', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.tandai-semua');

    Route::get('/sekolah', [SekolahController::class, 'edit'])->name('sekolah.edit');
    Route::put('/sekolah', [SekolahController::class, 'update'])->name('sekolah.update');

    // Master Data
    Route::resource('jabatan', JabatanController::class)->except(['show']);
    Route::resource('pegawai', PegawaiController::class)->except(['show']);
    Route::resource('kategori-surat', KategoriSuratController::class)->except(['show']);
    Route::resource('template-surat', TemplateSuratController::class)->except(['show']);
    Route::delete('/template-surat/variabel/{variabel}', [TemplateSuratController::class, 'hapusVariabel'])->name('template-surat.variabel.hapus');

    // Surat Keluar
    Route::controller(SuratKeluarController::class)->prefix('surat-keluar')->name('surat-keluar.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{suratKeluar}', 'show')->name('show');
        Route::post('/{suratKeluar}/ajukan', 'ajukan')->name('ajukan');
        Route::get('/{suratKeluar}/cetak-pdf', 'cetakPdf')->name('cetak-pdf');
    });

    // Approval
    Route::controller(ApprovalSuratKeluarController::class)->prefix('approval')->name('approval.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{approval}/setujui', 'setujui')->name('setujui');
        Route::post('/{approval}/tolak', 'tolak')->name('tolak');
    });

    // Surat Masuk & Disposisi
    Route::controller(SuratMasukController::class)->prefix('surat-masuk')->name('surat-masuk.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{suratMasuk}', 'show')->name('show');
    });
    Route::post('/surat-masuk/{suratMasuk}/disposisi', [DisposisiSuratMasukController::class, 'store'])->name('surat-masuk.disposisi.store');
    Route::post('/disposisi/{disposisi}/tindaklanjuti', [DisposisiSuratMasukController::class, 'tindaklanjuti'])->name('disposisi.tindaklanjuti');
    Route::post('/disposisi/{disposisi}/selesaikan', [DisposisiSuratMasukController::class, 'selesaikan'])->name('disposisi.selesaikan');
});
