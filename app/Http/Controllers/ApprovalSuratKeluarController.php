<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSuratKeluar;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use App\Models\PenomoranSurat;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApprovalSuratKeluarController extends Controller
{
    // Daftar surat yang menunggu approval dari user yang sedang login
    public function index()
    {
        $pegawaiId = Auth::user()->pegawai?->id_pegawai;

        $approvalSaya = ApprovalSuratKeluar::with(['suratKeluar.kategori', 'suratKeluar.pembuat.pegawai'])
            ->where('id_pegawai_pemberi_approval', $pegawaiId)
            ->where('status', 'menunggu')
            ->latest('id_approval')
            ->paginate(15);

        return view('approval.index', compact('approvalSaya'));
    }

    public function setujui(Request $request, ApprovalSuratKeluar $approval)
    {
        $this->pastikanApproverSah($approval);

        $approval->setujui($request->catatan);
        LogAktivitas::catat('ubah_data', 'Approval Surat Keluar', "Menyetujui surat: {$approval->suratKeluar->perihal}");

        $suratKeluar = $approval->suratKeluar;
        $approvalBerikutnya = $suratKeluar->approval()->where('status', 'menunggu')->first();

        if ($approvalBerikutnya) {
            // Masih ada approval tahap berikutnya - notifikasi ke approver selanjutnya
            if ($approvalBerikutnya->pegawaiPemberiApproval?->user) {
                Notifikasi::kirim(
                    $approvalBerikutnya->pegawaiPemberiApproval->user->id_user, 'keluar',
                    $suratKeluar->id_surat_keluar, 'Surat menunggu persetujuan Anda',
                    "Surat \"{$suratKeluar->perihal}\" perlu Anda setujui."
                );
            }
        } else {
            // Semua approval selesai -> generate nomor resmi & terbitkan
            $this->terbitkanSurat($suratKeluar);
        }

        return back()->with('sukses', 'Surat berhasil disetujui.');
    }

    public function tolak(Request $request, ApprovalSuratKeluar $approval)
    {
        $request->validate(['catatan' => 'required|string|max:255']);

        $this->pastikanApproverSah($approval);

        $approval->tolak($request->catatan);
        $approval->suratKeluar->update(['status' => 'draft']);

        LogAktivitas::catat('ubah_data', 'Approval Surat Keluar', "Menolak surat: {$approval->suratKeluar->perihal}");

        if ($approval->suratKeluar->pembuat) {
            Notifikasi::kirim(
                $approval->suratKeluar->dibuat_oleh, 'keluar', $approval->suratKeluar->id_surat_keluar,
                'Surat ditolak', "Surat \"{$approval->suratKeluar->perihal}\" ditolak: {$request->catatan}"
            );
        }

        return back()->with('sukses', 'Surat ditolak dan dikembalikan ke draft.');
    }

    protected function pastikanApproverSah(ApprovalSuratKeluar $approval): void
    {
        abort_unless(
            $approval->id_pegawai_pemberi_approval === Auth::user()->pegawai?->id_pegawai,
            403,
            'Anda bukan approver untuk surat ini.'
        );
    }

    protected function terbitkanSurat(SuratKeluar $suratKeluar): void
    {
        $noUrut = PenomoranSurat::nomorUrutBerikutnya(
            $suratKeluar->id_unit_pembuat, $suratKeluar->id_kategori, (int) now()->format('Y')
        );

        $formatNomor = $suratKeluar->template->format_nomor;
        $nomorSurat = str_replace(
            ['{kode_klasifikasi}', '{kode_sekolah}', '{kode_unit}', '{tahun}', '{no_urut}'],
            [
                $suratKeluar->klasifikasi->kode_klasifikasi ?? '420.5',
                'SMKN-07',
                $suratKeluar->unitPembuat->kode_unit ?? '-',
                now()->format('Y'),
                $noUrut,
            ],
            $formatNomor
        );

        $isiSurat = $suratKeluar->template->isi_template;
        $dataUntukRender = array_merge($suratKeluar->data_variabel ?? [], [
            'tanggal_surat' => $suratKeluar->tanggal_surat?->translatedFormat('d F Y'),
        ]);
        foreach ($dataUntukRender as $key => $value) {
            $isiSurat = str_replace('{{'.$key.'}}', $value, $isiSurat);
        }

        $suratKeluar->update([
            'nomor_surat' => $nomorSurat,
            'isi_surat' => $isiSurat,
            'status' => 'terkirim',
        ]);

        if ($suratKeluar->pembuat) {
            Notifikasi::kirim(
                $suratKeluar->dibuat_oleh, 'keluar', $suratKeluar->id_surat_keluar,
                'Surat terbit', "Surat \"{$suratKeluar->perihal}\" sudah terbit dengan nomor {$nomorSurat}."
            );
        }
    }
}
