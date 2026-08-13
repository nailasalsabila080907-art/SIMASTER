<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SekolahController extends Controller
{
    public function edit()
    {
        $sekolah = Sekolah::firstOrFail();

        return view('master.sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request)
    {
        $sekolah = Sekolah::firstOrFail();

        $data = $request->validate([
            'nama_sekolah' => ['required', 'string', 'max:150'],
            'alamat' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'website' => ['nullable', 'string', 'max:150'],
            'nama_kepala_sekolah' => ['nullable', 'string', 'max:100'],
            'nip_kepala_sekolah' => ['nullable', 'string', 'max:30'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'kop_surat' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        unset($data['logo'], $data['kop_surat']);

        if ($request->hasFile('logo')) {
            if ($sekolah->logo_path && Storage::disk('public')->exists($sekolah->logo_path)) {
                Storage::disk('public')->delete($sekolah->logo_path);
            }

            $data['logo_path'] = $request->file('logo')->store('logo-sekolah', 'public');
        }

        if ($request->hasFile('kop_surat')) {
            if ($sekolah->kop_surat_path && Storage::disk('public')->exists($sekolah->kop_surat_path)) {
                Storage::disk('public')->delete($sekolah->kop_surat_path);
            }

            $data['kop_surat_path'] = $request->file('kop_surat')->store('kop-surat', 'public');
        }

        $sekolah->update($data);

        return back()->with('sukses', 'Profil sekolah berhasil diperbarui.');
    }
}
