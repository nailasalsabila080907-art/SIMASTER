<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('id_user', Auth::id())
            ->latest('created_at')
            ->paginate(15);

        return view('notifikasi.index', compact('notifikasi'));
    }

    public function tandaiDibaca(Notifikasi $notifikasi)
    {
        // Pastikan notifikasi hanya bisa dibuka oleh pemiliknya
        abort_unless(
            $notifikasi->id_user === Auth::id(),
            403
        );

        // Tandai sudah dibaca
        $notifikasi->tandaiDibaca();

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI SURAT KELUAR
        |--------------------------------------------------------------------------
        | id_surat di tabel notifikasi masih menyimpan
        | id_surat_keluar, sedangkan route SuratKeluar
        | sekarang menggunakan UUID.
        */
        if ($notifikasi->tipe_surat === 'keluar') {

            $suratKeluar = SuratKeluar::where(
                'id_surat_keluar',
                $notifikasi->id_surat
            )->firstOrFail();

            $idPegawai = Auth::user()->pegawai->id_pegawai;

            $masihMenungguApproval = $idPegawai && $suratKeluar
                ->approval()
                ->where('id_pegawai_pemberi_approval', $idPegawai)
                ->where('status', 'menunggu')
                ->exists();
            
            if ($masihMenungguApproval) {
                return redirect()->route('approval.index');
            }
            
            return redirect()->route(
                'surat-keluar.show',
                $suratKeluar
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI DISPOSISI SURAT MASUK
        |--------------------------------------------------------------------------
        */

        if ($notifikasi->id_disposisi) {
            return redirect()->route(
                'disposisi.show',
                $notifikasi->id_disposisi
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NOTIFIKASI SURAT MASUK
        |--------------------------------------------------------------------------
        */

        if ($notifikasi->tipe_surat === 'masuk') {

            $suratMasuk = SuratMasuk::where(
                'id_surat_masuk',
                $notifikasi->id_surat
            )->firstOrFail();

            return redirect()->route(
                'surat-masuk.show',
                $suratMasuk
            );
        }

        return redirect()
            ->route('notifikasi.index')
            ->with('gagal', 'Jenis notifikasi tidak dikenali.');
    }

    public function tandaiSemuaDibaca() 
    {
        Notifikasi::where('id_user', Auth::id())
            ->where('sudah_dibaca', false)
            ->update([
                'sudah_dibaca' => true
            ]);

        return back()->with(
            'sukses',
            'Semua notifikasi ditandai sudah dibaca.'
        );
    }
}