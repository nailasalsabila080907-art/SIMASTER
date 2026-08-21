<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSuratKeluar;
use App\Models\LogAktivitas;
use App\Models\LogAktivitasSurat;
use App\Models\Notifikasi;
use App\Models\PenomoranSurat;
use App\Models\SuratKeluar;
use Barryvdh\DomPDF\Facade\Pdf;
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
        abort_unless(
            $approval->id_pegawai_pemberi_approval ===
                Auth::user()->pegawai?->id_pegawai,
            403,
            'Anda bukan approver untuk surat ini.'
        );

        abort_if(
            $approval->status !== 'menunggu',
            422,
            'Approval ini sudah diproses.'
        );
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

        abort_if(
            $adaSebelumnya,
            422,
            'Tahap approval sebelumnya belum selesai.'
        );
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

        $sekolah = $suratKeluar
            ->unitPembuat
            ?->sekolah;

        $formatNomor =
            $suratKeluar->template->format_nomor;

        $nomorSurat = str_replace(
            [
                '{kode_klasifikasi}',
                '{kode_sekolah}',
                '{kode_unit}',
                '{tahun}',
                '{no_urut}',
            ],
            [
                $suratKeluar
                    ->klasifikasi
                    ?->kode_klasifikasi
                    ?? '420.5',

                $sekolah
                    ?->kode_surat
                    ?? 'SMKN-07',

                $suratKeluar
                    ->unitPembuat
                    ?->kode_unit
                    ?? '-',

                $tahun,

                str_pad(
                    (string) $noUrut,
                    3,
                    '0',
                    STR_PAD_LEFT
                ),
            ],
            $formatNomor
        );

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
                'Surat terbit',
                "Surat \"{$suratKeluar->perihal}\" sudah terbit dengan nomor {$nomorSurat}."
            );
        }
    }
}
