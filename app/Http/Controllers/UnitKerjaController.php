<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Models\UnitKerja;
use Illuminate\Http\Request;

class UnitKerjaController extends Controller
{
    public function index()
    {
        return view('master.unit-kerja.index', ['unitKerja' => UnitKerja::with('sekolah')->orderBy('nama_unit')->paginate(15)]);
    }

    public function create()
    {
        return view('master.unit-kerja.form', ['unit' => new UnitKerja(), 'sekolah' => Sekolah::firstOrFail()]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['id_sekolah'] = Sekolah::firstOrFail()->id_sekolah;
        UnitKerja::create($data);
        return redirect()->route('unit-kerja.index')->with('sukses', 'Unit kerja berhasil ditambahkan.');
    }

    public function edit(UnitKerja $unitKerja)
    {
        return view('master.unit-kerja.form', ['unit' => $unitKerja, 'sekolah' => Sekolah::firstOrFail()]);
    }

    public function update(Request $request, UnitKerja $unitKerja)
    {
        $unitKerja->update($this->validasi($request, $unitKerja->id_unit));
        return redirect()->route('unit-kerja.index')->with('sukses', 'Unit kerja berhasil diperbarui.');
    }

    public function destroy(UnitKerja $unitKerja)
    {
        if ($unitKerja->pegawai()->exists() || $unitKerja->penomoranSurat()->exists()) {
            return back()->with('gagal', 'Unit kerja masih digunakan data lain dan tidak dapat dihapus.');
        }
        $unitKerja->delete();
        return redirect()->route('unit-kerja.index')->with('sukses', 'Unit kerja berhasil dihapus.');
    }

    private function validasi(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'kode_unit' => ['required', 'string', 'max:20'],
            'nama_unit' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);
    }
}
