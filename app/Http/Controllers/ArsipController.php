<?php

namespace App\Http\Controllers;

use App\Models\ArsipSurat;
use App\Models\LogAktivitas;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArsipController extends Controller
{
   public function index(Request $request)
{
    $bolehLihatSemua = in_array(
        Auth::user()->role,
        ['admin_tu', 'super_admin', 'kepala_sekolah']
    );

    // Filter
    $filterCari = trim($request->get('cari', ''));
    $filterUserId = $request->get('user_id');
    $filterTipeSurat = $request->get('tipe_surat');

    // Query arsip
    $query = ArsipSurat::with('pengarsip')
        ->latest('tanggal_diarsipkan');

    // Hak akses pengguna
    if (!$bolehLihatSemua) {
        $query->where('diarsipkan_oleh', Auth::id());
    }

    // Filter pengguna
    if ($bolehLihatSemua && $filterUserId) {
        $query->where('diarsipkan_oleh', $filterUserId);
    }

    // Filter jenis surat
    if ($filterTipeSurat) {
        $query->where('tipe_surat', $filterTipeSurat);
    }


    if ($filterCari !== '') {

        $query->where(function ($q) use ($filterCari) {

            // Cari berdasarkan ID surat
            $q->where('id_surat', 'like', "%{$filterCari}%");

            // Cari berdasarkan pengarsip
            $q->orWhereHas('pengarsip', function ($userQuery) use ($filterCari) {

                $userQuery->where('username', 'like', "%{$filterCari}%")
                    ->orWhereHas('pegawai', function ($pegawaiQuery) use ($filterCari) {
                        $pegawaiQuery->where(
                            'nama_lengkap',
                            'like',
                            "%{$filterCari}%"
                        );
                    });

            });

            // Cari surat keluar
            $q->orWhere(function ($suratQuery) use ($filterCari) {

                $suratQuery
                    ->where('tipe_surat', 'keluar')
                    ->whereExists(function ($subQuery) use ($filterCari) {

                        $subQuery->select(DB::raw(1))
                            ->from('surat_keluar')
                            ->whereColumn(
                                'surat_keluar.id_surat_keluar',
                                'arsip_surat.id_surat'
                            )
                            ->where(function ($sq) use ($filterCari) {

                                $sq->where(
                                    'surat_keluar.nomor_surat',
                                    'like',
                                    "%{$filterCari}%"
                                )
                                ->orWhere(
                                    'surat_keluar.perihal',
                                    'like',
                                    "%{$filterCari}%"
                                );

                            });

                    });

            });

            // Cari surat masuk
            $q->orWhere(function ($suratQuery) use ($filterCari) {

                $suratQuery
                    ->where('tipe_surat', 'masuk')
                    ->whereExists(function ($subQuery) use ($filterCari) {

                        $subQuery->select(DB::raw(1))
                            ->from('surat_masuk')
                            ->whereColumn(
                                'surat_masuk.id_surat_masuk',
                                'arsip_surat.id_surat'
                            )
                            ->where(function ($sq) use ($filterCari) {

                                $sq->where(
                                    'surat_masuk.nomor_surat_masuk',
                                    'like',
                                    "%{$filterCari}%"
                                )
                                ->orWhere(
                                    'surat_masuk.perihal',
                                    'like',
                                    "%{$filterCari}%"
                                );

                            });

                    });

            });

        });
    }

    $arsip = $query
        ->paginate(20)
        ->withQueryString();

    // Daftar pengguna
    $daftarUser = User::with('pegawai')
        ->whereHas('pegawai')
        ->orderBy('username')
        ->get();

    return view('arsip.index', compact(
        'arsip',
        'bolehLihatSemua',
        'daftarUser',
        'filterCari',
        'filterUserId',
        'filterTipeSurat'
    ));
}

    public function arsipkanKeluar(SuratKeluar $suratKeluar)
    {
        abort_unless($suratKeluar->status === 'terkirim', 422, 'Hanya surat yang sudah terbit yang dapat diarsipkan.');
        DB::transaction(function () use ($suratKeluar) {
            ArsipSurat::firstOrCreate(
                ['tipe_surat' => 'keluar', 'id_surat' => $suratKeluar->id_surat_keluar],
                ['tahun_arsip' => (int) ($suratKeluar->tanggal_surat?->format('Y') ?? now()->year), 'diarsipkan_oleh' => auth::id()]
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
                ['tahun_arsip' => (int) ($suratMasuk->tanggal_diterima?->format('Y') ?? now()->year), 'diarsipkan_oleh' => auth::id()]
            );
            $suratMasuk->update(['status' => 'diarsipkan']);
            LogAktivitas::catat('ubah_data', 'Arsip', "Mengarsipkan surat masuk: {$suratMasuk->perihal}");
        });
        return back()->with('sukses', 'Surat masuk berhasil diarsipkan.');
    }
}
