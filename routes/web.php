<?php

use App\Http\Controllers\ApprovalSuratKeluarController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiSuratMasukController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KategoriSuratController;
use App\Http\Controllers\KlasifikasiArsipController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\LogAktivitasSuratController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SekolahController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TemplateSuratController;
use App\Http\Controllers\UnitKerjaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil.index');
    Route::get('/profil/edit', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/keamanan', [ProfilController::class, 'updateKeamanan'])->name('profil.keamanan.update');
    Route::get('/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log-aktivitas.index');
    Route::get('/log-surat', [LogAktivitasSuratController::class, 'index'])->name('log-surat.index');  

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::post('/notifikasi/{notifikasi}/tandai-dibaca', [NotifikasiController::class, 'tandaiDibaca'])->name('notifikasi.tandai-dibaca');
    Route::post('/notifikasi/tandai-semua', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('notifikasi.tandai-semua');

    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');
    Route::post('/arsip/surat-keluar/{suratKeluar}', [ArsipController::class, 'arsipkanKeluar'])->name('arsip.surat-keluar');
    Route::post('/arsip/surat-masuk/{suratMasuk}', [ArsipController::class, 'arsipkanMasuk'])->name('arsip.surat-masuk');

    Route::middleware('role:admin_tu,super_admin')->group(function () {
        Route::get('/sekolah', [SekolahController::class, 'edit'])->name('sekolah.edit');
        Route::put('/sekolah', [SekolahController::class, 'update'])->name('sekolah.update');

        Route::resource('jabatan', JabatanController::class)->except(['show']);
        Route::get('/jabatan-sampah', [JabatanController::class, 'trashed'])->name('jabatan.trashed');
        Route::put('/jabatan/{uuid}/restore', [JabatanController::class, 'restore'])->name('jabatan.restore');
        Route::delete('/jabatan/{uuid}/force', [JabatanController::class, 'forceDelete'])->name('jabatan.forceDelete');

        Route::resource('pegawai', PegawaiController::class)->except(['show']);
         Route::get('/pegawai-sampah', [PegawaiController::class, 'trashed'])->name('pegawai.trashed');
    Route::put('/pegawai/{uuid}/restore', [PegawaiController::class, 'restore'])->name('pegawai.restore');
    Route::delete('/pegawai/{uuid}/force', [PegawaiController::class, 'forceDelete'])->name('pegawai.forceDelete');

        Route::resource('kategori-surat', KategoriSuratController::class)->parameters(['kategori-surat' => 'kategoriSurat'])->except(['show']);
        Route::resource('template-surat', TemplateSuratController::class)->parameters(['template-surat' => 'templateSurat'])->except(['show']);
        Route::delete('/template-surat/variabel/{variabel}', [TemplateSuratController::class, 'hapusVariabel'])->name('template-surat.variabel.hapus');
        Route::resource('unit-kerja', UnitKerjaController::class)->parameters(['unit-kerja' => 'unitKerja'])->except(['show']);
        Route::resource('jurusan', JurusanController::class)->except(['show']);
        Route::resource('klasifikasi-arsip', KlasifikasiArsipController::class)->parameters(['klasifikasi-arsip' => 'klasifikasiArsip'])->except(['show']);
        Route::get('/pengguna', [PenggunaController::class, 'index'])->name('pengguna.index');
        Route::get('/pengguna/{pengguna}/edit', [PenggunaController::class, 'edit'])->name('pengguna.edit');
        Route::put('/pengguna/{pengguna}', [PenggunaController::class, 'update'])->name('pengguna.update');
        Route::delete('/pengguna/{pengguna}', [PenggunaController::class, 'destroy'])->name('pengguna.destroy');
        Route::get('/pengguna-sampah', [PenggunaController::class, 'trashed'])->name('pengguna.trashed');
    Route::put('/pengguna/{uuid}/restore', [PenggunaController::class, 'restore'])->name('pengguna.restore');
    Route::delete('/pengguna/{uuid}/force', [PenggunaController::class, 'forceDelete'])->name('pengguna.forceDelete');

    });

    Route::controller(SuratKeluarController::class)->prefix('surat-keluar')->name('surat-keluar.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{suratKeluar}/edit', 'edit')->name('edit');
        Route::put('/{suratKeluar}', 'update')->name('update');
        Route::get('/{suratKeluar}', 'show')->name('show');
        Route::post('/{suratKeluar}/ajukan', 'ajukan')->name('ajukan');
        Route::get('/{suratKeluar}/cetak-pdf', 'cetakPdf')->name('cetak-pdf');
        Route::get('/surat-keluar/{suratKeluar}/cetak-pdf', [SuratKeluarController::class, 'cetakPdf'])->name('surat-keluar.cetak-pdf');
    });


Route::middleware(['auth', 'role:admin_tu,super_admin,kepala_sekolah,wakil_kepala_sekolah'])->group(function () {

    Route::get('/approval', [ApprovalSuratKeluarController::class, 'index'])
        ->name('approval.index');

    Route::post('/approval/{approval}/setujui', [ApprovalSuratKeluarController::class, 'setujui'])
        ->name('approval.setujui');

    Route::post('/approval/{approval}/tolak', [ApprovalSuratKeluarController::class, 'tolak'])
        ->name('approval.tolak');

});
    Route::controller(SuratMasukController::class)->prefix('surat-masuk')->name('surat-masuk.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{suratMasuk}', 'show')->name('show');
    });

    Route::post('/surat-masuk/{suratMasuk}/disposisi', [DisposisiSuratMasukController::class, 'store'])->name('surat-masuk.disposisi.store');
    Route::controller(DisposisiSuratMasukController::class)->prefix('disposisi')->name('disposisi.')->group(function () {
        Route::get('/{disposisi}', 'show')->name('show');
        Route::post('/{disposisi}/tindaklanjuti', 'tindaklanjuti')->name('tindaklanjuti');
        Route::post('/{disposisi}/selesaikan', 'selesaikan')->name('selesaikan');
    });
});
