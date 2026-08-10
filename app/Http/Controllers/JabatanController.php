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
}
