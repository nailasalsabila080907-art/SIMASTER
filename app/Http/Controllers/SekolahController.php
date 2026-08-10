<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

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
            'nama_sekolah' => 'required|string|max:150',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|string|max:100',
            'nama_kepala_sekolah' => 'nullable|string|max:100',
            'nip_kepala_sekolah' => 'nullable|string|max:30',
            'logo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logo-sekolah', 'public');
            $data['logo_path'] = $path;
        }

        unset($data['logo']);
        $sekolah->update($data);

        return back()->with('sukses', 'Profil sekolah berhasil diperbarui.');
    }
}
