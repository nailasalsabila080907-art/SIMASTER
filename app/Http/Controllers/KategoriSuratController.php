<?php

namespace App\Http\Controllers;

use App\Models\KategoriSurat;
use Illuminate\Http\Request;

class KategoriSuratController extends Controller
{
    public function index()
    {
        return view('master.kategori-surat.index', ['kategori' => KategoriSurat::orderBy('nama_kategori')->paginate(15)]);
    }

    public function create()
    {
        return view('master.kategori-surat.form', ['kategori' => new KategoriSurat()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'jenis' => 'required|in:masuk,keluar,umum',
            'keterangan' => 'nullable|string|max:255',
        ]);

        KategoriSurat::create($data);

        return redirect()->route('kategori-surat.index')->with('sukses', 'Kategori surat berhasil ditambahkan.');
    }

    public function edit(KategoriSurat $kategoriSurat)
    {
        return view('master.kategori-surat.form', ['kategori' => $kategoriSurat]);
    }

    public function update(Request $request, KategoriSurat $kategoriSurat)
    {
        $data = $request->validate([
            'nama_kategori' => 'required|string|max:100',
            'jenis' => 'required|in:masuk,keluar,umum',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $kategoriSurat->update($data);

        return redirect()->route('kategori-surat.index')->with('sukses', 'Kategori surat berhasil diperbarui.');
    }

    public function destroy(KategoriSurat $kategoriSurat)
    {
        if ($kategoriSurat->templateSurat()->exists()) {
            return back()->with('gagal', 'Kategori ini masih dipakai template surat, tidak bisa dihapus.');
        }

        $kategoriSurat->delete();

        return redirect()->route('kategori-surat.index')->with('sukses', 'Kategori surat berhasil dihapus.');
    }
}
