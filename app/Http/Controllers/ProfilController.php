<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $user->load('pegawai.jabatan', 'pegawai.unitKerja', 'pegawai.jurusan', 'pegawai.sekolah');

        return view('profil.index', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('pegawai.jabatan', 'pegawai.unitKerja', 'pegawai.jurusan', 'pegawai.sekolah');

        return view('profil.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $pegawai = $user->pegawai;

        if (! $pegawai) {
            return back()->with('gagal', 'Data pegawai untuk akun ini belum tersedia.');
        }

        $data = $request->validate([
            'gelar_depan' => ['nullable', 'string', 'max:20'],
            'gelar_belakang' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date'],
            'pangkat_golongan' => ['nullable', 'string', 'max:50'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:100'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id_user, 'id_user')],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        if ($request->hasFile('foto')) {
            if ($pegawai->foto_path && Storage::disk('public')->exists($pegawai->foto_path)) {
                Storage::disk('public')->delete($pegawai->foto_path);
            }

            $data['foto_path'] = $request->file('foto')->store('foto-pegawai', 'public');
        }

        $pegawai->update([
            'gelar_depan' => $data['gelar_depan'] ?? null,
            'gelar_belakang' => $data['gelar_belakang'] ?? null,
            'jenis_kelamin' => $data['jenis_kelamin'],
            'tempat_lahir' => $data['tempat_lahir'] ?? null,
            'tanggal_lahir' => $data['tanggal_lahir'] ?? null,
            'pangkat_golongan' => $data['pangkat_golongan'] ?? null,
            'no_hp' => $data['no_hp'] ?? null,
            'email' => $data['email'] ?? null,
            'foto_path' => $data['foto_path'] ?? $pegawai->foto_path,
        ]);

        $user->username = $data['username'];
        if (! empty($data['password'])) {
            $user->password_hash = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('profil.index')->with('sukses', 'Profil berhasil diperbarui.');
    }
}
