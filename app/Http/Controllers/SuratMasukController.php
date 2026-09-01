<?php

namespace App\Http\Controllers;

use App\Models\ArsipSurat;
use App\Models\KategoriSurat;
use App\Models\KlasifikasiArsip;
use App\Models\LogAktivitas;
use App\Models\LogAktivitasSurat;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratMasuk::with(['kategori', 'penerima.pegawai'])->latest('tanggal_diterima');

        if ($request->filled('status')) $query->where('status', $request->status);
        if (! in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true)) $query->where('diterima_oleh', Auth::id());

        return view('surat-masuk.index', [
            'suratMasuk' => $query->paginate(15)->withQueryString(),
            'filterStatus' => $request->status,
        ]);
    }

    public function create()
    {
        return view('surat-masuk.create', [
            'kategoriList' => KategoriSurat::whereIn('jenis', ['masuk', 'umum'])->orderBy('nama_kategori')->get(),
            'klasifikasiList' => KlasifikasiArsip::orderBy('kode_klasifikasi')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_surat_asal' => 'nullable|string|max:100',
            'asal_instansi' => 'required|string|max:150',
            'id_kategori' => 'required|exists:kategori_surat,id_kategori',
            'id_klasifikasi' => 'required|exists:klasifikasi_arsip,id_klasifikasi',
            'perihal' => 'required|string|max:255',
            'tanggal_surat' => 'nullable|date',
            'tanggal_diterima' => 'required|date',
            'sifat_surat' => 'required|in:biasa,penting,segera,rahasia',
            'file_scan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $filePath = $request->hasFile('file_scan') ? $request->file('file_scan')->store('surat-masuk', 'public') : null;
        $nomorAgenda = 'AGD-'.now()->format('Ymd').'-'.str_pad((string) (SuratMasuk::whereDate('created_at', now()->toDateString())->count() + 1), 3, '0', STR_PAD_LEFT);

        $suratMasuk = SuratMasuk::create([
            ...$data,
            'nomor_surat_masuk' => $nomorAgenda,
            'file_scan_path' => $filePath,
            'status' => 'baru',
            'diterima_oleh' => Auth::id(),
        ]);

        LogAktivitas::catat('tambah_data', 'Surat Masuk', "Mencatat surat masuk: {$suratMasuk->perihal}");
        LogAktivitasSurat::catat(
        LogAktivitasSurat::TIPE_MASUK,
        $suratMasuk->id_surat_masuk,
        LogAktivitasSurat::AKSI_DIBUAT,
        "Surat masuk dicatat dengan nomor agenda {$nomorAgenda}: {$suratMasuk->perihal}"
    );

        return redirect()->route('surat-masuk.show', $suratMasuk)->with('sukses', 'Surat masuk berhasil dicatat.');
    }
    

    public function show(SuratMasuk $suratMasuk)
    {
        if (! in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true)) {
            abort_unless($suratMasuk->diterima_oleh === Auth::id(), 403);
        }

        $suratMasuk->load(['kategori', 'klasifikasi', 'penerima.pegawai', 'disposisi.penerimaPegawai', 'disposisi.penerimaUnit', 'disposisi.pemberiDisposisi']);
        $pegawaiList = \App\Models\Pegawai::where('status', 'aktif')->orderBy('nama_lengkap')->get();
        $unitList = \App\Models\UnitKerja::where('status', 'aktif')->orderBy('nama_unit')->get();

        return view('surat-masuk.show', compact('suratMasuk', 'pegawaiList', 'unitList'));
    }

    // =====================================================
    // Soft delete - hapus (arsipkan ke sampah), lihat sampah,
    // pulihkan, dan hapus permanen. Setiap aksi dicatat ke
    // LogAktivitas (log umum) dan LogAktivitasSurat (log khusus surat).
    // =====================================================

    public function destroy(SuratMasuk $suratMasuk)
    {
        $this->pastikanPemilikAtauAdmin($suratMasuk);

        if ($suratMasuk->status === 'diarsipkan') {
            abort_unless(
                in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true),
                403,
                'Hanya admin yang bisa menghapus surat yang sudah diarsipkan.'
            );
        }

        $perihal = $suratMasuk->perihal;
        $idSurat = $suratMasuk->id_surat_masuk;

        // Hapus juga catatan arsipnya (kalau ada), biar tidak nyangkut jadi baris kosong di halaman Arsip Surat
        ArsipSurat::where('tipe_surat', 'masuk')->where('id_surat', $idSurat)->delete();

        $suratMasuk->delete();

        LogAktivitas::catat(
            'hapus_data',
            'Surat Masuk',
            "Menghapus surat masuk: {$perihal}"
        );
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $idSurat,
            LogAktivitasSurat::AKSI_HAPUS,
            "Surat masuk dipindahkan ke sampah: {$perihal}"
        );

        return redirect()
            ->route('surat-masuk.index')
            ->with('sukses', 'Surat masuk berhasil dihapus.');
    }

    public function trashed()
    {
        $query = SuratMasuk::onlyTrashed()
            ->with(['kategori', 'penerima.pegawai'])
            ->latest('deleted_at');

        if (! in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true)) {
            $query->where('diterima_oleh', Auth::id());
        }

        return view('surat-masuk.trashed', [
            'suratMasuk' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function restore(string $uuid)
    {
        $suratMasuk = SuratMasuk::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
        $this->pastikanPemilikAtauAdmin($suratMasuk);

        $suratMasuk->restore();

        LogAktivitas::catat(
            'ubah_data',
            'Surat Masuk',
            "Memulihkan surat masuk dari sampah: {$suratMasuk->perihal}"
        );
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_DIPULIHKAN,
            "Surat masuk dipulihkan dari sampah: {$suratMasuk->perihal}"
        );

        return back()->with('sukses', 'Surat masuk berhasil dipulihkan.');
    }

    public function forceDelete(string $uuid)
    {
        $suratMasuk = SuratMasuk::onlyTrashed()->where('uuid', $uuid)->firstOrFail();

        abort_unless(
            in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true),
            403,
            'Hanya admin yang bisa menghapus surat masuk secara permanen.'
        );

        $perihal = $suratMasuk->perihal;
        $idSurat = $suratMasuk->id_surat_masuk;

        LogAktivitas::catat(
            'hapus_data',
            'Surat Masuk',
            "Menghapus permanen surat masuk: {$perihal}"
        );
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $idSurat,
            LogAktivitasSurat::AKSI_HAPUS_PERMANEN,
            "Surat masuk dihapus permanen: {$perihal}"
        );

        $suratMasuk->forceDelete();

        return back()->with('sukses', 'Surat masuk berhasil dihapus permanen.');
    }

    protected function pastikanPemilikAtauAdmin(SuratMasuk $suratMasuk): void
    {
        abort_unless(
            $suratMasuk->diterima_oleh === Auth::id()
            || in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true),
            403,
            'Anda tidak memiliki akses ke surat ini.'
        );
    }
}