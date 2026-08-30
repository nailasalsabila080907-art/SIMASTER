<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::orderBy('level_jabatan', 'desc')->paginate(15);
        return view('master.jabatan.index', compact('jabatan'));
    }

    public function create()
    {
        return view('master.jabatan.form', ['jabatan' => new Jabatan()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'level_jabatan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Jabatan::create($data);

        return redirect()->route('jabatan.index')->with('sukses', 'Jabatan berhasil ditambahkan.');
    }

    public function edit(Jabatan $jabatan)
    {
        return view('master.jabatan.form', compact('jabatan'));
    }

    public function update(Request $request, Jabatan $jabatan)
    {
        $data = $request->validate([
            'nama_jabatan' => 'required|string|max:100',
            'level_jabatan' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $jabatan->update($data);

        return redirect()->route('jabatan.index')->with('sukses', 'Jabatan berhasil diperbarui.');
    }

    public function destroy(Jabatan $jabatan)
    {
        if ($jabatan->pegawai()->exists()) {
            return back()->with('gagal', 'Jabatan ini masih dipakai pegawai, tidak bisa dihapus.');
        }

        $jabatan->delete();

        return redirect()->route('jabatan.index')->with('sukses', 'Jabatan berhasil dihapus.');
    }

    // =====================================================
    // TAMBAHAN: Soft delete - halaman sampah, restore, dan
    // hapus permanen.
    // =====================================================

    public function trashed()
    {
        $jabatan = Jabatan::onlyTrashed()->orderBy('level_jabatan', 'desc')->paginate(15);
        return view('master.jabatan.trashed', compact('jabatan'));
    }

    public function restore($uuid)
    {
        $jabatan = Jabatan::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
        $jabatan->restore();

        return back()->with('sukses', 'Jabatan berhasil dipulihkan.');
    }

    public function forceDelete($uuid)
    {
        $jabatan = Jabatan::onlyTrashed()->where('uuid', $uuid)->firstOrFail();

        // withTrashed() di sini penting: kita tidak mau jabatan dihapus
        // permanen kalau masih ada pegawai (termasuk yang sudah di-soft-delete)
        // yang id_jabatan-nya menunjuk ke sini. Kalau pegawai itu suatu saat
        // di-restore, dia akan menunjuk ke jabatan yang sudah tidak ada.
        if ($jabatan->pegawai()->withTrashed()->exists()) {
            return back()->with('gagal', 'Jabatan ini masih tercatat dipakai pegawai (termasuk yang ada di sampah), tidak bisa dihapus permanen.');
        }

        $jabatan->forceDelete();

        return back()->with('sukses', 'Jabatan berhasil dihapus permanen.');
    }
}