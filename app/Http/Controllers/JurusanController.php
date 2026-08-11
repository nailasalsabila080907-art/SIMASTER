<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        return view('master.jurusan.index', ['jurusan' => Jurusan::orderBy('nama_jurusan')->paginate(15)]);
    }

    public function create()
    {
        return view('master.jurusan.form', ['jurusan' => new Jurusan()]);
    }

    public function store(Request $request)
    {
        Jurusan::create($this->validasi($request) + ['id_sekolah' => Sekolah::firstOrFail()->id_sekolah]);
        return redirect()->route('jurusan.index')->with('sukses', 'Jurusan berhasil ditambahkan.');
    }

    public function edit(Jurusan $jurusan)
    {
        return view('master.jurusan.form', compact('jurusan'));
    }

    public function update(Request $request, Jurusan $jurusan)
    {
        $jurusan->update($this->validasi($request));
        return redirect()->route('jurusan.index')->with('sukses', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan)
    {
        if ($jurusan->pegawai()->exists()) return back()->with('gagal', 'Jurusan masih dipakai pegawai.');
        $jurusan->delete();
        return redirect()->route('jurusan.index')->with('sukses', 'Jurusan berhasil dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'kode_jurusan' => ['required', 'string', 'max:20'],
            'nama_jurusan' => ['required', 'string', 'max:100'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);
    }
}
