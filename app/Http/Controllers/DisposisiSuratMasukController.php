<?php

namespace App\Http\Controllers;

use App\Models\DisposisiSuratMasuk;
use App\Models\LogAktivitas;
use App\Models\LogAktivitasSurat;
use App\Models\Notifikasi;
use App\Models\Pegawai;
use App\Models\SuratMasuk;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DisposisiSuratMasukController extends Controller
{
    // =====================================================
    // ALUR BARU:
    // 1. TU ajukan surat ke Kepsek (ajukanKeKepsek)
    // 2. Kepsek tentukan tujuan (pegawai/unit) lalu setujui (setujuiKepsek),
    //    ATAU tolak dengan catatan (tolakKepsek)
    // 3. TU kirim disposisi yang sudah disiapkan Kepsek ke penerima (kirim)
    // =====================================================

    public function ajukanKeKepsek(Request $request, SuratMasuk $suratMasuk)
    {
        abort_unless(in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true), 403);

        abort_unless(
            $suratMasuk->status === 'baru',
            422,
            'Surat ini sudah diproses, tidak bisa diajukan lagi.'
        );

        $data = $request->validate([
            'catatan_pengantar' => 'nullable|string|max:500',
        ]);

        $suratMasuk->update([
            'status' => 'menunggu_approval_kepsek',
            'catatan_kepsek' => null,
        ]);

        $kepsekList = User::where('role', 'kepala_sekolah')->where('status', 'aktif')->get();
        foreach ($kepsekList as $kepsek) {
            Notifikasi::kirim(
                $kepsek->id_user,
                'masuk',
                $suratMasuk->id_surat_masuk,
                null,
                'Surat menunggu persetujuan Anda',
                "Surat \"{$suratMasuk->perihal}\" perlu Anda tentukan tujuan disposisinya."
            );
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Mengajukan surat ke Kepsek: {$suratMasuk->perihal}");
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_DIAJUKAN,
            "Surat diajukan ke Kepala Sekolah" . (($data['catatan_pengantar'] ?? null) ? ": {$data['catatan_pengantar']}" : '')
        );

        return back()->with('sukses', 'Surat berhasil diajukan ke Kepala Sekolah.');
    }

    public function setujuiKepsek(Request $request, SuratMasuk $suratMasuk)
    {
        abort_unless(in_array(Auth::user()->role, ['kepala_sekolah', 'super_admin'], true), 403);

        abort_unless(
            $suratMasuk->status === 'menunggu_approval_kepsek',
            422,
            'Surat ini tidak sedang menunggu persetujuan Anda.'
        );

        $data = $request->validate([
            'tujuan_pegawai' => 'nullable|array',
            'tujuan_pegawai.*' => 'integer|exists:pegawai,id_pegawai',
            'tujuan_unit' => 'nullable|array',
            'tujuan_unit.*' => 'integer|exists:unit_kerja,id_unit',
            'instruksi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $totalTujuan = count($data['tujuan_pegawai'] ?? []) + count($data['tujuan_unit'] ?? []);
        abort_if($totalTujuan === 0, 422, 'Pilih minimal satu tujuan (pegawai atau unit).');

        $pegawaiKepsek = Auth::user()->pegawai;
        abort_unless($pegawaiKepsek, 422, 'Akun Anda belum terhubung dengan data pegawai.');

        DB::transaction(function () use ($data, $suratMasuk, $pegawaiKepsek) {
            foreach ($data['tujuan_pegawai'] ?? [] as $idPegawai) {
                DisposisiSuratMasuk::create([
                    'id_surat_masuk' => $suratMasuk->id_surat_masuk,
                    'dari_pegawai' => $pegawaiKepsek->id_pegawai,
                    'ke_pegawai' => $idPegawai,
                    'instruksi' => $data['instruksi'] ?? null,
                    'catatan' => $data['catatan'] ?? null,
                    'status' => 'siap_kirim',
                ]);
            }

            foreach ($data['tujuan_unit'] ?? [] as $idUnit) {
                DisposisiSuratMasuk::create([
                    'id_surat_masuk' => $suratMasuk->id_surat_masuk,
                    'dari_pegawai' => $pegawaiKepsek->id_pegawai,
                    'ke_unit' => $idUnit,
                    'instruksi' => $data['instruksi'] ?? null,
                    'catatan' => $data['catatan'] ?? null,
                    'status' => 'siap_kirim',
                ]);
            }

            $suratMasuk->update([
                'status' => 'siap_kirim',
                'catatan_kepsek' => $data['catatan'] ?? null,
            ]);
        });

        // Notifikasi ke semua Admin TU biar tau surat ini siap dikirim ke unit
        $tuList = User::where('role', 'admin_tu')->where('status', 'aktif')->get();
        foreach ($tuList as $tu) {
            Notifikasi::kirim(
                $tu->id_user,
                'masuk',
                $suratMasuk->id_surat_masuk,
                null,
                'Surat siap dikirim ke unit',
                "Surat \"{$suratMasuk->perihal}\" sudah disetujui Kepala Sekolah, silakan kirim ke unit tujuan."
            );
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Menyetujui dan menentukan tujuan disposisi: {$suratMasuk->perihal}");
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_APPROVE,
            "Disetujui Kepala Sekolah, tujuan disposisi ditentukan ({$totalTujuan} tujuan)"
        );

        return back()->with('sukses', 'Surat disetujui. Menunggu Admin TU mengirim disposisi ke unit tujuan.');
    }

    public function tolakKepsek(Request $request, SuratMasuk $suratMasuk)
    {
        abort_unless(in_array(Auth::user()->role, ['kepala_sekolah', 'super_admin'], true), 403);

        abort_unless(
            $suratMasuk->status === 'menunggu_approval_kepsek',
            422,
            'Surat ini tidak sedang menunggu persetujuan Anda.'
        );

        $data = $request->validate([
            'catatan_penolakan' => 'required|string|max:500',
        ]);

        $suratMasuk->update([
            'status' => 'baru',
            'catatan_kepsek' => $data['catatan_penolakan'],
        ]);

        $tuList = User::where('role', 'admin_tu')->where('status', 'aktif')->get();
        foreach ($tuList as $tu) {
            Notifikasi::kirim(
                $tu->id_user,
                'masuk',
                $suratMasuk->id_surat_masuk,
                null,
                'Pengajuan surat ditolak',
                "Surat \"{$suratMasuk->perihal}\" ditolak Kepala Sekolah: {$data['catatan_penolakan']}"
            );
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Menolak pengajuan surat: {$suratMasuk->perihal}");
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_TOLAK,
            "Ditolak Kepala Sekolah: {$data['catatan_penolakan']}"
        );

        return back()->with('sukses', 'Surat ditolak dan dikembalikan ke Admin TU.');
    }

    public function kirim(SuratMasuk $suratMasuk)
    {
        abort_unless(in_array(Auth::user()->role, ['admin_tu', 'super_admin'], true), 403);

        abort_unless(
            $suratMasuk->status === 'siap_kirim',
            422,
            'Surat ini belum siap dikirim.'
        );

        $disposisiList = $suratMasuk->disposisi()->where('status', 'siap_kirim')->get();
        abort_if($disposisiList->isEmpty(), 422, 'Tidak ada disposisi yang siap dikirim untuk surat ini.');

        foreach ($disposisiList as $disposisi) {
            $disposisi->update(['status' => 'menunggu']);

            if ($disposisi->ke_pegawai && $disposisi->penerimaPegawai?->user) {
                Notifikasi::kirim(
                    $disposisi->penerimaPegawai->user->id_user,
                    'masuk',
                    $suratMasuk->id_surat_masuk,
                    $disposisi->id_disposisi,
                    'Disposisi surat baru',
                    "Surat \"{$suratMasuk->perihal}\" didisposisikan kepada Anda."
                );
            } elseif ($disposisi->ke_unit) {
                $pegawaiUnit = Pegawai::where('id_unit', $disposisi->ke_unit)
                    ->where('status', 'aktif')
                    ->with('user')
                    ->get();

                foreach ($pegawaiUnit as $pegawai) {
                    if ($pegawai->user) {
                        Notifikasi::kirim(
                            $pegawai->user->id_user,
                            'masuk',
                            $suratMasuk->id_surat_masuk,
                            $disposisi->id_disposisi,
                            'Disposisi surat baru',
                            "Surat \"{$suratMasuk->perihal}\" didisposisikan ke {$disposisi->penerimaUnit?->nama_unit}."
                        );
                    }
                }
            }
        }

        $suratMasuk->update(['status' => 'didisposisi']);

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Mengirim disposisi ke {$disposisiList->count()} tujuan: {$suratMasuk->perihal}");
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_DISPOSISI,
            "Disposisi dikirim ke {$disposisiList->count()} tujuan"
        );

        return back()->with('sukses', 'Disposisi berhasil dikirim ke semua tujuan.');
    }

    // =====================================================
    // Method lama - sekarang khusus super_admin sebagai jalur darurat
    // (bypass alur approval Kepsek, langsung disposisi satu tujuan)
    // =====================================================

    public function store(Request $request, SuratMasuk $suratMasuk)
    {
        abort_unless(Auth::user()->role === 'super_admin', 403);

        $data = $request->validate([
            'tujuan_tipe' => 'required|in:pegawai,unit',
            'tujuan_id' => 'required|integer',
            'instruksi' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $pegawaiPengirim = Auth::user()->pegawai;
        abort_unless($pegawaiPengirim, 422, 'Akun Anda belum terhubung dengan data pegawai.');

        if ($data['tujuan_tipe'] === 'pegawai') {
            $penerima = Pegawai::where('status', 'aktif')->findOrFail($data['tujuan_id']);
            $kePegawai = $penerima->id_pegawai;
            $keUnit = null;
        } else {
            $unit = UnitKerja::where('status', 'aktif')->findOrFail($data['tujuan_id']);
            $kePegawai = null;
            $keUnit = $unit->id_unit;
        }

        $disposisi = DisposisiSuratMasuk::create([
            'id_surat_masuk' => $suratMasuk->id_surat_masuk,
            'dari_pegawai' => $pegawaiPengirim->id_pegawai,
            'ke_pegawai' => $kePegawai,
            'ke_unit' => $keUnit,
            'instruksi' => $data['instruksi'] ?? null,
            'catatan' => $data['catatan'] ?? null,
            'status' => 'menunggu',
        ]);

        $suratMasuk->update(['status' => 'didisposisi']);

        if ($kePegawai && $penerima->user) {
            Notifikasi::kirim(
                $penerima->user->id_user,
                'masuk',
                $suratMasuk->id_surat_masuk,
                $disposisi->id_disposisi,
                'Disposisi surat baru',
                "Surat \"{$suratMasuk->perihal}\" didisposisikan kepada Anda."
            );
        } elseif ($keUnit) {
            $pegawaiUnit = Pegawai::where('id_unit', $keUnit)
                ->where('status', 'aktif')
                ->with('user')
                ->get();

            foreach ($pegawaiUnit as $pegawai) {
                if ($pegawai->user) {
                    Notifikasi::kirim(
                        $pegawai->user->id_user,
                        'masuk',
                        $suratMasuk->id_surat_masuk,
                        $disposisi->id_disposisi,
                        'Disposisi surat baru',
                        "Surat \"{$suratMasuk->perihal}\" didisposisikan ke {$unit->nama_unit}."
                    );
                }
            }
        }

        LogAktivitas::catat('tambah_data', 'Disposisi Surat Masuk', "Membuat disposisi untuk surat: {$suratMasuk->perihal}");

        $tujuanNama = $kePegawai ? $penerima->nama_lengkap : $unit->nama_unit;
        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_DISPOSISI,
            "Didisposisikan ke {$tujuanNama}" . ($data['instruksi'] ? ": {$data['instruksi']}" : '')
        );

        return back()->with('sukses', 'Disposisi berhasil dibuat.');
    }

    public function tindaklanjuti(DisposisiSuratMasuk $disposisi)
    {
        $this->bolehKelola($disposisi);
        $disposisi->update(['status' => 'ditindaklanjuti']);

        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $disposisi->id_surat_masuk,
            LogAktivitasSurat::AKSI_TINDAK_LANJUT,
            "Disposisi mulai ditindaklanjuti"
        );

        return back()->with('sukses', 'Disposisi ditandai sedang ditindaklanjuti.');
    }

    public function selesaikan(DisposisiSuratMasuk $disposisi)
    {
        $this->bolehKelola($disposisi);
        $disposisi->update(['status' => 'selesai', 'tanggal_selesai' => now()]);

        $suratMasuk = $disposisi->suratMasuk;
        if ($suratMasuk->disposisi()->where('status', '!=', 'selesai')->doesntExist()) {
            $suratMasuk->update(['status' => 'selesai']);
        }

        LogAktivitas::catat('ubah_data', 'Disposisi Surat Masuk', "Menyelesaikan disposisi surat: {$suratMasuk->perihal}");

        LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_MASUK,
            $suratMasuk->id_surat_masuk,
            LogAktivitasSurat::AKSI_DISPOSISI_SELESAI,
            "Disposisi selesai ditindaklanjuti"
        );

        if ($suratMasuk->status === 'selesai') {
            LogAktivitasSurat::catat(
                LogAktivitasSurat::TIPE_MASUK,
                $suratMasuk->id_surat_masuk,
                LogAktivitasSurat::AKSI_SELESAI,
                "Semua disposisi selesai, surat ditandai selesai"
            );
        }

        return back()->with('sukses', 'Disposisi ditandai selesai.');
    }

    public function show(DisposisiSuratMasuk $disposisi)
    {
        $pegawaiId = Auth::user()->pegawai?->id_pegawai;

        $bolehLihat =
            in_array(
                Auth::user()->role,
                ['admin_tu', 'super_admin', 'kepala_sekolah'],
                true
            )
            || $disposisi->ke_pegawai === $pegawaiId
            || (
                $disposisi->ke_unit
                && Auth::user()->pegawai?->id_unit === $disposisi->ke_unit
            );

        abort_unless(
            $bolehLihat,
            403,
            'Anda tidak memiliki akses ke lembar disposisi ini.'
        );

        $disposisi->load([
            'suratMasuk.kategori',
            'suratMasuk.klasifikasi',
            'pemberiDisposisi',
            'penerimaPegawai',
            'penerimaUnit',
        ]);

        $sekolah = \App\Models\Sekolah::first();

        return view('disposisi.show', compact(
            'disposisi',
            'sekolah'
        ));
    }

    private function bolehKelola(DisposisiSuratMasuk $disposisi): void
    {
        $pegawaiId = Auth::user()->pegawai?->id_pegawai;
        $bolehAdmin = in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true);
        $bolehPenerima = $disposisi->ke_pegawai === $pegawaiId;
        abort_unless($bolehAdmin || $bolehPenerima, 403, 'Anda tidak memiliki akses ke disposisi ini.');
    }
}