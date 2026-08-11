<?php

namespace App\Http\Controllers;

use App\Models\KategoriSurat;
use App\Models\TemplateSurat;
use App\Models\VariabelTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateSuratController extends Controller
{
    public function index()
    {
        return view('master.template-surat.index', [
            'template' => TemplateSurat::with('kategori')->orderBy('nama_template')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('master.template-surat.form', [
            'template' => new TemplateSurat(),
            'kategoriList' => KategoriSurat::orderBy('nama_kategori')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        $template = TemplateSurat::create([
            ...$data,
            'is_active' => $request->boolean('is_active', true),
            'created_by' => Auth::id(),
        ]);

        $this->simpanVariabel($request, $template);

        return redirect()->route('template-surat.edit', $template)->with('sukses', 'Template berhasil dibuat. Sekarang tambahkan field dinamisnya.');
    }

    public function edit(TemplateSurat $templateSurat)
    {
        $templateSurat->load('variabel');
        return view('master.template-surat.form', [
            'template' => $templateSurat,
            'kategoriList' => KategoriSurat::orderBy('nama_kategori')->get(),
        ]);
    }

    public function update(Request $request, TemplateSurat $templateSurat)
    {
        $data = $this->validasi($request, $templateSurat->id_template);
        $templateSurat->update($data);
        $this->simpanVariabel($request, $templateSurat);

        return redirect()->route('template-surat.edit', $templateSurat)->with('sukses', 'Template berhasil diperbarui.');
    }

    public function destroy(TemplateSurat $templateSurat)
    {
        if ($templateSurat->suratKeluar()->exists() ?? false) {
            return back()->with('gagal', 'Template ini sudah dipakai surat, tidak bisa dihapus. Nonaktifkan saja.');
        }

        $templateSurat->delete();
        return redirect()->route('template-surat.index')->with('sukses', 'Template berhasil dihapus.');
    }

    protected function validasi(Request $request, ?int $idTemplate = null): array
    {
        return $request->validate([
            'id_kategori' => 'required|exists:kategori_surat,id_kategori',
            'nama_template' => 'required|string|max:150',
            'kode_template' => 'required|string|max:30|unique:template_surat,kode_template,'.$idTemplate.',id_template',
            'isi_template' => 'required|string',
            'format_nomor' => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ]);
    }

    // Tambah field variabel baru lewat form (field kosong di baris terakhir diabaikan)
    protected function simpanVariabel(Request $request, TemplateSurat $template): void
    {
        if (! $request->filled('variabel_baru_nama')) {
            return;
        }

        foreach ($request->variabel_baru_nama as $i => $nama) {
            if (blank($nama)) continue;

            VariabelTemplate::create([
                'id_template' => $template->id_template,
                'nama_variabel' => \Illuminate\Support\Str::snake($nama),
                'label' => $request->variabel_baru_label[$i] ?? $nama,
                'tipe_input' => $request->variabel_baru_tipe[$i] ?? 'text',
                'wajib' => true,
            ]);
        }
    }

    public function hapusVariabel(VariabelTemplate $variabel)
    {
        $templateId = $variabel->id_template;
        $variabel->delete();
        return redirect()->route('template-surat.edit', $templateId)->with('sukses', 'Field berhasil dihapus.');
    }
}
