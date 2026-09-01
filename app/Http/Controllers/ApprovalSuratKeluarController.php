<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSuratKeluar;
use App\Models\ArsipSurat;
use App\Models\LogAktivitas;
use App\Models\LogAktivitasSurat;
use App\Models\Notifikasi;
use App\Models\PenomoranSurat;
use App\Models\SuratKeluar;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovalSuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $pegawaiId = Auth::user()->pegawai?->id_pegawai;

        $semua = ApprovalSuratKeluar::with([
            'suratKeluar.kategori',
            'suratKeluar.pembuat.pegawai',
            'suratKeluar.approval',
        ])
            ->where('id_pegawai_pemberi_approval', $pegawaiId)
            ->where('status', 'menunggu')
            ->latest('id_approval')
            ->get()
            ->filter(function (ApprovalSuratKeluar $approval) {
                return ! $approval->suratKeluar->approval
                    ->where('urutan', '<', $approval->urutan)
                    ->contains(function ($sebelumnya) {
                        return $sebelumnya->status !== 'disetujui';
                    });
            })
            ->values();

        $perPage = 15;

        $page = LengthAwarePaginator::resolveCurrentPage();

        $items = $semua
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $approvalSaya = new LengthAwarePaginator(
            $items,
            $semua->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view(
            'approval.index',
            compact('approvalSaya')
        );
    }

    public function setujui(
        Request $request,
        ApprovalSuratKeluar $approval
    ) {
        $this->pastikanApproverSah($approval);

        $this->pastikanTahapBerjalan($approval);

        DB::transaction(function () use ($approval, $request) {
            $approval->setujui(
                $request->input('catatan')
            );

            $suratKeluar = $approval
                ->suratKeluar()
                ->with([
                    'approval',
                    'pembuat',
                    'template',
                    'kategori',
                    'klasifikasi',
                    'unitPembuat',
                ])
                ->firstOrFail();

            LogAktivitas::catat(
                'ubah_data',
                'Approval Surat Keluar',
                "Menyetujui surat: {$suratKeluar->perihal}"
            );
            LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_KELUAR,
            $suratKeluar->id_surat_keluar,
            LogAktivitasSurat::AKSI_APPROVE,
            "Disetujui pada tahap urutan ke-{$approval->urutan}" .
                ($request->input('catatan') ? ": {$request->input('catatan')}" : '')
        );

            $approvalBerikutnya = $suratKeluar
                ->approval()
                ->where('status', 'menunggu')
                ->orderBy('urutan')
                ->first();

            if ($approvalBerikutnya) {
                $userBerikutnya =
                    $approvalBerikutnya
                        ->pegawaiPemberiApproval
                        ?->user;

                if ($userBerikutnya) {
                    Notifikasi::kirim(
                        $userBerikutnya->id_user,
                        'keluar',
                        $suratKeluar->id_surat_keluar,
                        null,
                        'Surat menunggu persetujuan Anda',
                        "Surat \"{$suratKeluar->perihal}\" perlu Anda setujui."
                    );
                }
            } else {
                $this->terbitkanSurat($suratKeluar);
            }
        });

        return back()->with(
            'sukses',
            'Persetujuan berhasil diproses.'
        );
    }

    public function tolak(
        Request $request,
        ApprovalSuratKeluar $approval
    ) {
        $data = $request->validate([
            'catatan' => 'required|string|max:255',
        ]);

        $this->pastikanApproverSah($approval);

        $this->pastikanTahapBerjalan($approval);

        DB::transaction(function () use (
            $approval,
            $data
        ) {
            $suratKeluar = $approval
                ->suratKeluar()
                ->with('pembuat')
                ->firstOrFail();

            $approval->tolak(
                $data['catatan']
            );

            $suratKeluar
                ->approval()
                ->where('status', 'menunggu')
                ->update([
                    'status' => 'ditolak',
                    'tanggal_approval' => now(),
                ]);

            $suratKeluar->update([
                'status' => 'ditolak',
            ]);

        LogAktivitas::catat(
        'ubah_data',
        'Approval Surat Keluar',
        "Menolak surat: {$suratKeluar->perihal}"
    );

        LogAktivitasSurat::catat(
        LogAktivitasSurat::TIPE_KELUAR,
        $suratKeluar->id_surat_keluar,
        LogAktivitasSurat::AKSI_TOLAK,
        "Ditolak pada tahap urutan ke-{$approval->urutan}: {$data['catatan']}"
    );

            if ($suratKeluar->pembuat) {
                Notifikasi::kirim(
                    $suratKeluar->dibuat_oleh,
                    'keluar',
                    $suratKeluar->id_surat_keluar,
                    null,
                    'Surat ditolak',
                    "Surat \"{$suratKeluar->perihal}\" ditolak: {$data['catatan']}"
                );
            }
        });

        return back()->with(
            'sukses',
            'Surat ditolak dan dikembalikan untuk diperbaiki.'
        );
    }

    protected function pastikanApproverSah(
        ApprovalSuratKeluar $approval
    ): void {
        if ($approval->id_pegawai_pemberi_approval !== Auth::user()->pegawai?->id_pegawai) {
            throw new HttpResponseException(
                back()->with('gagal', 'Anda bukan approver untuk surat ini.'
                )
            );
        }
        if ($approval->status !== 'menunggu') {
            throw new HttpResponseException(
                back()->with('gagal', 'Surat ini sudah diproses sebelumnya.'
                )
            );
        }
    }

    protected function pastikanTahapBerjalan(
        ApprovalSuratKeluar $approval
    ): void {
        $adaSebelumnya = $approval
            ->suratKeluar()
            ->firstOrFail()
            ->approval()
            ->where(
                'urutan',
                '<',
                $approval->urutan
            )
            ->where(
                'status',
                '!=',
                'disetujui'
            )
            ->exists();

        if($adaSebelumnya) {
            throw new HttpResponseException(
                back()->with('gagal', 'Tahap approval sebelumnya belum disetujui.')
            );
        }
    }

    protected function terbitkanSurat(
        SuratKeluar $suratKeluar
    ): void {
        if (! empty($suratKeluar->nomor_surat)) {
            return;
        }

        $suratKeluar->loadMissing([
            'template',
            'klasifikasi',
            'unitPembuat',
            'pembuat',
        ]);

        $tahun = (int) (
            $suratKeluar->tanggal_surat?->format('Y')
            ?? now()->format('Y')
        );

        $noUrut = PenomoranSurat::nomorUrutBerikutnya(
            $suratKeluar->id_unit_pembuat,
            $suratKeluar->id_kategori,
            $tahun
        );

        $formatNomor =
            $suratKeluar->template->format_nomor;

        $bangunNomorSurat = function (int $noUrut) use ($suratKeluar, $formatNomor) {
            return str_replace(
                [
                    '{kode_klasifikasi}',
                    '{kode_sekolah}',
                    '{kode_unit}',
                    '{tahun}',
                    '{no_urut}',
                ],
                [
                    $suratKeluar->klasifikasi?->kode_klasifikasi ?? '420.5',
                    $suratKeluar->unitPembuat?->sekolah?->kode_surat ?? 'SMKN-07',
                    $suratKeluar->unitPembuat?->kode_unit ?? '-',
                    (int) ($suratKeluar->tanggal_surat?->format('Y') ?? now()->format('Y')),
                    str_pad((string) $noUrut, 3, '0', STR_PAD_LEFT),
                ],
                $formatNomor
            );
        };

        $nomorSurat = $bangunNomorSurat($noUrut);

        $percobaan = 0;
        while (
            SuratKeluar::where('nomor_surat', $nomorSurat)
                ->where('id_surat_keluar', '!=', $suratKeluar->id_surat_keluar)
                ->exists()
            && $percobaan < 20
        ) {
            $noUrut = PenomoranSurat::nomorUrutBerikutnya(
                $suratKeluar->id_unit_pembuat,
                $suratKeluar->id_kategori,
                $tahun
            );
            $nomorSurat = $bangunNomorSurat($noUrut);
            $percobaan++;
        }

        $isiSurat =
            $suratKeluar
                ->template
                ->isi_template;

        $dataUntukRender = array_merge(
            $suratKeluar->data_variabel ?? [],
            [
                'nomor_surat' => $nomorSurat,
                'tanggal_surat' =>
                    $suratKeluar
                        ->tanggal_surat
                        ?->format('d F Y'),
            ]
        );

        foreach (
            $suratKeluar->template->variabel
            as $variabel
        ) {
            if (
                $variabel->tipe_input === 'date' &&
                ! empty(
                    $dataUntukRender[
                        $variabel->nama_variabel
                    ]
                )
            ) {
                try {
                    $dataUntukRender[
                        $variabel->nama_variabel
                    ] = Carbon::parse(
                        $dataUntukRender[
                            $variabel->nama_variabel
                        ]
                    )->format('d F Y');
                } catch (\Throwable $e) {
                }
            }
        }

        foreach (
            $dataUntukRender as $key => $value
        ) {
            $isiSurat = str_replace(
                '{{' . $key . '}}',
                e((string) $value),
                $isiSurat
            );
        }

        $suratKeluar->update([
            'nomor_surat' => $nomorSurat,
            'isi_surat' => $isiSurat,
            'status' => 'terkirim',
        ]);

        try {
            $directory =
                storage_path(
                    'app/public/surat-keluar'
                );

            if (! is_dir($directory)) {
                mkdir(
                    $directory,
                    0755,
                    true
                );
            }

            $namaFile =
                'surat-' .
                $suratKeluar->id_surat_keluar .
                '-' .
                now()->format('YmdHis') .
                '.pdf';

            Pdf::loadView(
                'pdf.surat-keluar',
                compact('suratKeluar')
            )
                ->setPaper(
                    'a4',
                    'portrait'
                )
                ->save(
                    $directory .
                    '/' .
                    $namaFile
                );

            $suratKeluar->update([
                'file_final_path' =>
                    'surat-keluar/' .
                    $namaFile,
            ]);
        } catch (\Throwable $e) {
            LogAktivitas::catat(
                'ubah_data',
                'Surat Keluar',
                'Surat terbit, tetapi PDF belum tersimpan otomatis: ' .
                    $e->getMessage()
            );
        }

            LogAktivitas::catat(
            'ubah_data',
            'Surat Keluar',
            "Surat terbit dengan nomor {$nomorSurat}"
        );

            LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_KELUAR,
            $suratKeluar->id_surat_keluar,
            LogAktivitasSurat::AKSI_TERBIT,
            "Surat resmi terbit dengan nomor {$nomorSurat}"
        );
        

        if ($suratKeluar->pembuat) {
            Notifikasi::kirim(
                $suratKeluar->dibuat_oleh,
                'keluar',
                $suratKeluar->id_surat_keluar,
                null,
                'Surat terbit',
                "Surat \"{$suratKeluar->perihal}\" sudah terbit dan masuk catatan tahunan dengan nomor {$nomorSurat}."
            );
        }

        ArsipSurat::firstOrCreate(
            ['tipe_surat' => 'keluar', 'id_surat' => $suratKeluar->id_surat_keluar],
            [ 
                'tahun_arsip' => (int) ($suratKeluar->tanggal_surat?->format('Y') ?? now()->format('Y')),
                'tanggal_diarsipkan' => now(),
                'diarsipkan_oleh' => $suratKeluar->dibuat_oleh,
            ]
        );

        $suratKeluar->update([
            'status' => 'diarsipkan',
        ]);

        LogAktivitas::catat(
            'ubah_data',
            'Arsip Surat',
            "Surat keluar otomatis masuk catatan tahunan: {$suratKeluar->perihal} (Nomor: {$nomorSurat})"
        );

        $userTU = User::where('status', 'aktif')
            ->whereIn('role', ['admin_tu', 'super_admin'])
            ->get();

        foreach ($userTU as $user) {
            Notifikasi::kirim(
                $user->id_user,
                'keluar',
                $suratKeluar->id_surat_keluar,
                null,
                'Surat Terbit',
                "Surat \"{$suratKeluar->perihal}\" sudah terbit dan masuk catatan tahunan dengan nomor {$nomorSurat}."
            );
        }
    }
}