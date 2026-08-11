<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('master.pengguna.index', ['pengguna' => User::with('pegawai.jabatan')->orderBy('username')->paginate(15)]);
    }

    public function edit(User $pengguna)
    {
        return view('master.pengguna.form', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username,'.$pengguna->id_user.',id_user'],
            'role' => ['required', 'in:super_admin,admin_tu,kepala_sekolah,staff,guru,operator'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $pengguna->username = $data['username'];
        $pengguna->role = $data['role'];
        $pengguna->status = $data['status'];
        if (! empty($data['password'])) $pengguna->password_hash = Hash::make($data['password']);
        $pengguna->save();

        return redirect()->route('pengguna.index')->with('sukses', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        if ($pengguna->id_user === auth()->id()) return back()->with('gagal', 'Akun yang sedang digunakan tidak boleh dihapus.');
        $pengguna->delete();
        return redirect()->route('pengguna.index')->with('sukses', 'Akun pengguna berhasil dihapus.');
    }
}
