<?php

namespace App\Http\Controllers;

use App\Models\KlasifikasiArsip;
use Illuminate\Http\Request;

class KlasifikasiArsipController extends Controller
{
    public function index()
    {
        return view('master.klasifikasi-arsip.index', ['klasifikasi' => KlasifikasiArsip::orderBy('kode_klasifikasi')->paginate(15)]);
    }

    public function create()
    {
        return view('master.klasifikasi-arsip.form', ['klasifikasi' => new KlasifikasiArsip(), 'parentList' => KlasifikasiArsip::orderBy('kode_klasifikasi')->get()]);
    }

    public function store(Request $request)
    {
        KlasifikasiArsip::create($this->validasi($request));
        return redirect()->route('klasifikasi-arsip.index')->with('sukses', 'Klasifikasi berhasil ditambahkan.');
    }

    public function edit(KlasifikasiArsip $klasifikasiArsip)
    {
        return view('master.klasifikasi-arsip.form', ['klasifikasi' => $klasifikasiArsip, 'parentList' => KlasifikasiArsip::where('id_klasifikasi', '!=', $klasifikasiArsip->id_klasifikasi)->orderBy('kode_klasifikasi')->get()]);
    }

    public function update(Request $request, KlasifikasiArsip $klasifikasiArsip)
    {
        $klasifikasiArsip->update($this->validasi($request, $klasifikasiArsip->id_klasifikasi));
        return redirect()->route('klasifikasi-arsip.index')->with('sukses', 'Klasifikasi berhasil diperbarui.');
    }

    public function destroy(KlasifikasiArsip $klasifikasiArsip)
    {
        if ($klasifikasiArsip->suratKeluar()->exists() || $klasifikasiArsip->suratMasuk()->exists()) {
            return back()->with('gagal', 'Klasifikasi masih digunakan surat.');
        }
        $klasifikasiArsip->delete();
        return redirect()->route('klasifikasi-arsip.index')->with('sukses', 'Klasifikasi berhasil dihapus.');
    }

    private function validasi(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'kode_klasifikasi' => ['required', 'string', 'max:20', 'unique:klasifikasi_arsip,kode_klasifikasi,'.$id.',id_klasifikasi'],
            'nama_klasifikasi' => ['required', 'string', 'max:150'],
            'parent_id' => ['nullable', 'exists:klasifikasi_arsip,id_klasifikasi'],
        ]);
    }
}
