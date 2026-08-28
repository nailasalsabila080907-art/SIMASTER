<?php

namespace App\Http\Controllers;

use App\Models\ArsipSurat;
use App\Models\LogAktivitas;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ArsipController extends Controller
{
    public function index(Request $request)
    {
        $arsip = ArsipSurat::with('pengarsip')->latest('tanggal_diarsipkan')->paginate(20)->withQueryString();
        return view('arsip.index', compact('arsip'));
    }

    public function arsipkanKeluar(SuratKeluar $suratKeluar)
    {
        abort_unless($suratKeluar->status === 'terkirim', 422, 'Hanya surat yang sudah terbit yang dapat diarsipkan.');
        DB::transaction(function () use ($suratKeluar) {
            ArsipSurat::firstOrCreate(
                ['tipe_surat' => 'keluar', 'id_surat' => $suratKeluar->id_surat_keluar],
                ['tahun_arsip' => (int) ($suratKeluar->tanggal_surat?->format('Y') ?? now()->year), 'diarsipkan_oleh' => Auth::id()]
            );
            $suratKeluar->update(['status' => 'diarsipkan']);
            LogAktivitas::catat('ubah_data', 'Arsip', "Mengarsipkan surat keluar: {$suratKeluar->perihal}");
        });
        return back()->with('sukses', 'Surat keluar berhasil diarsipkan.');
    }

    public function arsipkanMasuk(SuratMasuk $suratMasuk)
    {
        abort_unless($suratMasuk->status === 'selesai', 422, 'Surat masuk harus selesai diproses sebelum diarsipkan.');
        DB::transaction(function () use ($suratMasuk) {
            ArsipSurat::firstOrCreate(
                ['tipe_surat' => 'masuk', 'id_surat' => $suratMasuk->id_surat_masuk],
                ['tahun_arsip' => (int) ($suratMasuk->tanggal_diterima?->format('Y') ?? now()->year), 'diarsipkan_oleh' => Auth::id()]
            );
            $suratMasuk->update(['status' => 'diarsipkan']);
            LogAktivitas::catat('ubah_data', 'Arsip', "Mengarsipkan surat masuk: {$suratMasuk->perihal}");
        });
        return back()->with('sukses', 'Surat masuk berhasil diarsipkan.');
    }
}
