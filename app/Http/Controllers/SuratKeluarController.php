<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSuratKeluar;
use App\Models\Jabatan;
use App\Models\KategoriSurat;
use App\Models\KlasifikasiArsip;
use App\Models\LogAktivitas;
use App\Models\LogAktivitasSurat;
use App\Models\Notifikasi;
use App\Models\Pegawai;
use App\Models\SuratKeluar;
use App\Models\TemplateSurat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::with([
            'kategori',
            'pembuat.pegawai',
        ])->latest('created_at');

        if (! in_array(
            Auth::user()->role,
            ['admin_tu', 'super_admin', 'kepala_sekolah'],
            true
        )) {
            $query->where('dibuat_oleh', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('surat-keluar.index', [
            'suratKeluar' => $query->paginate(15)->withQueryString(),
            'filterStatus' => $request->status,
        ]);
    }

    public function create(Request $request)
    {
        return $this->form($request);
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        $this->pastikanPemilikAtauAdmin($suratKeluar);

        abort_unless(
            in_array($suratKeluar->status, ['draft', 'ditolak'], true),
            422,
            'Surat hanya bisa diedit saat masih draft atau ditolak.'
        );

        $request = new Request([
            'kategori' => $suratKeluar->id_kategori,
            'template' => $suratKeluar->id_template,
        ]);

        return $this->form($request, $suratKeluar);
    }

    public function store(Request $request)
    {
        $template = TemplateSurat::with('variabel')
            ->findOrFail($request->input('id_template'));

        $this->validasiFormSurat($request, $template);

        $surat = SuratKeluar::create(
            $this->dataSurat($request, $template)
        );
        LogAktivitas::catat(
        'tambah_data',
        'Surat Keluar',
        "Membuat draft surat: {$surat->perihal}"
    );

        LogAktivitasSurat::catat(
        LogAktivitasSurat::TIPE_KELUAR,
        $surat->id_surat_keluar,
        LogAktivitasSurat::AKSI_DIBUAT,
        "Draft surat dibuat: {$surat->perihal}"
    );

        return redirect()
            ->route('surat-keluar.show', $surat)
            ->with('sukses', 'Draft surat berhasil disimpan.');
    }

    public function update(
        Request $request,
        SuratKeluar $suratKeluar
    ) {
        $this->pastikanPemilikAtauAdmin($suratKeluar);

        abort_unless(
            in_array($suratKeluar->status, ['draft', 'ditolak'], true),
            422,
            'Surat tidak dapat diubah pada status saat ini.'
        );

        $template = TemplateSurat::with('variabel')
            ->findOrFail($request->input('id_template'));

        $this->validasiFormSurat($request, $template);

        $suratKeluar->update(
            $this->dataSurat($request, $template)
        );

        $suratKeluar->approval()->delete();

        $suratKeluar->update([
            'status' => 'draft',
            'nomor_surat' => null,
            'isi_surat' => null,
        ]);

        LogAktivitas::catat(
            'ubah_data',
            'Surat Keluar',
            "Memperbarui draft surat: {$suratKeluar->perihal}"
        );
        LogAktivitasSurat::catat(
        LogAktivitasSurat::TIPE_KELUAR,
        $suratKeluar->id_surat_keluar,
        LogAktivitasSurat::AKSI_DIEDIT,
        "Draft surat diperbarui: {$suratKeluar->perihal}"
        );

        return redirect()
            ->route('surat-keluar.show', $suratKeluar)
            ->with('sukses', 'Draft surat berhasil diperbarui.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $this->pastikanBolehLihat($suratKeluar);

        $suratKeluar->load([
            'template.variabel',
            'kategori',
            'pembuat.pegawai',
            'approval.pegawaiPemberiApproval',
        ]);

        return view(
            'surat-keluar.show',
            compact('suratKeluar')
        );
    }

    public function ajukan(SuratKeluar $suratKeluar)
    {
        $this->pastikanPemilikAtauAdmin($suratKeluar);

        abort_unless(
            in_array($suratKeluar->status, ['draft', 'ditolak'], true),
            422,
            'Surat tidak dapat diajukan pada status saat ini.'
        );

        $suratKeluar->loadMissing('pembuat.pegawai');

        $pembuatPegawaiId = $suratKeluar->pembuat?->id_pegawai;

        $approverUsers = \App\Models\User::with('pegawai.jabatan')
            ->where('status', 'aktif')
            ->where('role', 'kepala_sekolah')

            ->whereHas('pegawai', function ($query) use ($pembuatPegawaiId) {
              $query->where('status', 'aktif')
              ->where('id_pegawai', '!=', $pembuatPegawaiId);
            })
            ->get()
            ->sortBy(
                fn ($user) =>
                    $user->pegawai?->jabatan?->level_jabatan ?? 999
            )
            ->groupBy(
                fn ($user) =>
                    $user->pegawai?->jabatan?->level_jabatan ?? 999
            )
            ->map(
                fn ($users) => $users->first()
            )
            ->values();

        if ($approverUsers->isEmpty()) {
            return back()->with(
                'gagal',
                'Belum ada approver aktif dengan level jabatan minimal 2. Isi Master Pegawai dan pastikan akun Kepala TU/Kepala Sekolah aktif.'
            );
        }

        DB::transaction(function () use (
            $suratKeluar,
            $approverUsers
        ) {
            $suratKeluar->approval()->delete();

            foreach ($approverUsers as $index => $user) {
                ApprovalSuratKeluar::create([
                    'id_surat_keluar' => $suratKeluar->id_surat_keluar,
                    'id_pegawai_pemberi_approval' => $user->id_pegawai,
                    'urutan' => $index + 1,
                    'status' => 'menunggu',
                ]);

                if ($index === 0) {
                    Notifikasi::kirim(
                        $user->id_user,
                        'keluar',
                        $suratKeluar->id_surat_keluar,
                        'Surat menunggu persetujuan Anda',
                        "Surat \"{$suratKeluar->perihal}\" perlu Anda setujui."
                    );
                }
            }

            $suratKeluar->update([
                'status' => 'diajukan',
            ]);

            LogAktivitas::catat(
                'ubah_data',
                'Surat Keluar',
                "Mengajukan surat: {$suratKeluar->perihal}"
            );
            LogAktivitasSurat::catat(
            LogAktivitasSurat::TIPE_KELUAR,
            $suratKeluar->id_surat_keluar,
            LogAktivitasSurat::AKSI_DIAJUKAN,
            "Diajukan untuk persetujuan {$approverUsers->count()} approver"
        );
        });

        return back()->with(
            'sukses',
            'Surat berhasil diajukan untuk persetujuan.'
        );
    }

    public function cetakPdf(SuratKeluar $suratKeluar)
{
    $this->pastikanBolehLihat($suratKeluar);

    abort_unless(
        in_array($suratKeluar->status, ['terkirim', 'diarsipkan'], true),
        404,
        'Surat belum terbit.'
    );

    $suratKeluar->load([
        'kategori',
        'unitPembuat.sekolah',
    ]);

    $sekolah = \App\Models\Sekolah::first();

    $pdf = Pdf::loadView(
        'surat-keluar.cetak-pdf',
        compact('suratKeluar', 'sekolah')
    )->setPaper('a4', 'portrait');
    LogAktivitasSurat::catat(
        LogAktivitasSurat::TIPE_KELUAR,
        $suratKeluar->id_surat_keluar,
        LogAktivitasSurat::AKSI_CETAK,
        "Surat dicetak PDF"
    );

    return $pdf->stream(
        'Surat-' .
        str_replace(
            ['/', ' '],
            '-',
            $suratKeluar->nomor_surat
        ) .
        '.pdf'
    );
}
    protected function form(
        Request $request,
        ?SuratKeluar $suratKeluar = null
    ) {
        $kategoriList = KategoriSurat::where(
            'jenis',
            'keluar'
        )
            ->orderBy('nama_kategori')
            ->get();

        $kategoriId =
            $request->input('kategori')
            ?: $suratKeluar?->id_kategori;

        $templateId =
            $request->input('template')
            ?: $suratKeluar?->id_template;

        $templateList = $kategoriId
            ? TemplateSurat::where(
                'id_kategori',
                $kategoriId
            )
                ->where('is_active', true)
                ->orderBy('nama_template')
                ->get()
            : collect();

        $template = $templateId
            ? TemplateSurat::with('variabel')
                ->find($templateId)
            : null;

        return view('surat-keluar.create', [
            'kategoriList' => $kategoriList,
            'templateList' => $templateList,
            'template' => $template,
            'kategoriTerpilih' => $kategoriId,
            'suratKeluar' => $suratKeluar,
        ]);
    }

    protected function validasiFormSurat(
        Request $request,
        TemplateSurat $template
    ): void {
        $request->validate([
            'id_template' => [
                'required',
                'exists:template_surat,id_template',
            ],
            'perihal' => [
                'required',
                'string',
                'max:255',
            ],
            'tujuan' => [
                'nullable',
                'string',
                'max:255',
            ],
            'tanggal_surat' => [
                'required',
                'date',
            ],
            'sifat_surat' => [
                'required',
                'in:biasa,penting,segera,rahasia',
            ],
        ]);

        foreach ($template->variabel as $var) {
            $rules = $var->wajib
                ? ['required']
                : ['nullable'];

            $rules[] = match ($var->tipe_input) {
                'date' => 'date',
                'number' => 'numeric',
                default => 'string',
            };

            $request->validate([
                'variabel_' . $var->id_variabel => $rules,
            ]);
        }
    }

    protected function dataSurat(
        Request $request,
        TemplateSurat $template
    ): array {
        $dataVariabel = [];

        foreach ($template->variabel as $var) {
            $dataVariabel[$var->nama_variabel] =
                $request->input(
                    'variabel_' . $var->id_variabel
                );
        }

        $pegawai = Auth::user()->pegawai;

        return [
            'id_template' => $template->id_template,
            'id_kategori' => $template->id_kategori,
            'id_klasifikasi' =>
                KlasifikasiArsip::firstOrFail()
                    ->id_klasifikasi,
            'id_unit_pembuat' =>
                $pegawai?->id_unit
                ?? abort(
                    422,
                    'Akun Anda belum memiliki unit kerja.'
                ),
            'perihal' => $request->perihal,
            'tujuan' => $request->tujuan,
            'data_variabel' => $dataVariabel,
            'tanggal_surat' => $request->tanggal_surat,
            'sifat_surat' => $request->sifat_surat,
            'status' => 'draft',
            'dibuat_oleh' => Auth::id(),
        ];
    }

    protected function pastikanPemilikAtauAdmin(
        SuratKeluar $suratKeluar
    ): void {
        abort_unless(
            $suratKeluar->dibuat_oleh === Auth::id()
            || in_array(
                Auth::user()->role,
                ['admin_tu', 'super_admin'],
                true
            ),
            403,
            'Anda tidak memiliki akses ke surat ini.'
        );
    }

    protected function pastikanBolehLihat(
        SuratKeluar $suratKeluar
    ): void {
        if (in_array(
            Auth::user()->role,
            [
                'admin_tu',
                'super_admin',
                'kepala_sekolah',
            ],
            true
        )) {
            return;
        }

        abort_unless(
            $suratKeluar->dibuat_oleh === Auth::id(),
            403,
            'Anda tidak memiliki akses ke surat ini.'
        );
    }

    
}