<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSuratKeluar;
use App\Models\Jabatan;
use App\Models\KategoriSurat;
use App\Models\KlasifikasiArsip;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use App\Models\Pegawai;
use App\Models\PenomoranSurat;
use App\Models\SuratKeluar;
use App\Models\TemplateSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::with(['kategori', 'pembuat.pegawai'])->latest('created_at');

        // Guru/staff biasa cuma lihat surat yang dia buat sendiri
        if (! in_array(Auth::user()->role, ['admin_tu', 'super_admin', 'kepala_sekolah'])) {
            $query->where('dibuat_oleh', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suratKeluar = $query->paginate(15)->withQueryString();

        return view('surat-keluar.index', [
            'suratKeluar' => $suratKeluar,
            'filterStatus' => $request->status,
        ]);
    }

    public function create(Request $request)
    {
        $kategoriList = KategoriSurat::where('jenis', 'keluar')->orderBy('nama_kategori')->get();

        $templateList = collect();
        if ($request->filled('kategori')) {
            $templateList = TemplateSurat::where('id_kategori', $request->kategori)
                ->where('is_active', true)->get();
        }

        $template = null;
        if ($request->filled('template')) {
            $template = TemplateSurat::with('variabel')->find($request->template);
        }

        return view('surat-keluar.create', [
            'kategoriList' => $kategoriList,
            'templateList' => $templateList,
            'template' => $template,
            'kategoriTerpilih' => $request->kategori,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_template' => 'required|exists:template_surat,id_template',
            'perihal' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
        ]);

        $template = TemplateSurat::with('variabel')->findOrFail($request->id_template);

        $dataVariabel = [];
        foreach ($template->variabel as $var) {
            $dataVariabel[$var->nama_variabel] = $request->input('variabel_'.$var->id_variabel);
        }

        $pegawai = Auth::user()->pegawai;

        $surat = SuratKeluar::create([
            'id_template' => $template->id_template,
            'id_kategori' => $template->id_kategori,
            'id_klasifikasi' => KlasifikasiArsip::first()?->id_klasifikasi,
            'id_unit_pembuat' => $pegawai?->id_unit,
            'perihal' => $request->perihal,
            'tujuan' => $request->tujuan,
            'data_variabel' => $dataVariabel,
            'tanggal_surat' => $request->tanggal_surat,
            'sifat_surat' => $request->sifat_surat ?? 'biasa',
            'status' => 'draft',
            'dibuat_oleh' => Auth::id(),
        ]);

        LogAktivitas::catat('tambah_data', 'Surat Keluar', "Membuat draft surat: {$surat->perihal}");

        return redirect()->route('surat-keluar.show', $surat)->with('sukses', 'Draft surat berhasil disimpan.');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load(['template.variabel', 'kategori', 'pembuat.pegawai', 'approval.pegawaiPemberiApproval']);

        return view('surat-keluar.show', compact('suratKeluar'));
    }

    // Ajukan draft ke alur approval berjenjang
    public function ajukan(SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->status !== 'draft') {
            return back()->with('gagal', 'Surat ini sudah diajukan sebelumnya.');
        }

        // Ambil 1 pegawai per level jabatan (level terkecil dulu) sebagai urutan approval
        $levelJabatan = Jabatan::where('level_jabatan', '>', 0)->orderBy('level_jabatan')->get();

        $urutan = 1;
        $adaApprover = false;
        foreach ($levelJabatan as $jabatan) {
            $approver = Pegawai::where('id_jabatan', $jabatan->id_jabatan)->where('status', 'aktif')->first();
            if ($approver) {
                ApprovalSuratKeluar::create([
                    'id_surat_keluar' => $suratKeluar->id_surat_keluar,
                    'id_pegawai_pemberi_approval' => $approver->id_pegawai,
                    'urutan' => $urutan,
                    'status' => 'menunggu',
                ]);

                if ($approver->user) {
                    Notifikasi::kirim(
                        $approver->user->id_user, 'keluar', $suratKeluar->id_surat_keluar,
                        'Surat menunggu persetujuan Anda',
                        "Surat \"{$suratKeluar->perihal}\" perlu Anda setujui."
                    );
                }

                $urutan++;
                $adaApprover = true;
            }
        }

        if (! $adaApprover) {
            return back()->with('gagal', 'Belum ada pegawai dengan jabatan approval (mis. Kepala Sekolah) terdaftar di Master Data. Tambahkan dulu sebelum mengajukan surat.');
        }

        $suratKeluar->update(['status' => 'diajukan']);
        LogAktivitas::catat('ubah_data', 'Surat Keluar', "Mengajukan surat: {$suratKeluar->perihal}");

        return back()->with('sukses', 'Surat berhasil diajukan untuk persetujuan.');
    }

    public function cetakPdf(SuratKeluar $suratKeluar)
    {
        abort_unless($suratKeluar->status === 'terkirim', 404, 'Surat belum terbit, belum bisa dicetak.');

        $suratKeluar->load('kategori');

        $pdf = Pdf::loadView('pdf.surat-keluar', compact('suratKeluar'))->setPaper('a4', 'portrait');

        $namaFile = 'Surat-'.str_replace(['/', ' '], '-', $suratKeluar->nomor_surat).'.pdf';

        return $pdf->stream($namaFile);
    }
}
