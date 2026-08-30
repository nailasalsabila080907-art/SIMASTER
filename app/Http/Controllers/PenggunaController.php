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
            // CATATAN sama seperti NIP di PegawaiController: username milik
            // akun yang sudah di-soft-delete tetap dianggap "sudah dipakai"
            // oleh rule unique bawaan ini. Tambahkan ->whereNull('deleted_at')
            // pakai Rule::unique() kalau kamu mau username bisa dipakai ulang.
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

    // =====================================================
    // TAMBAHAN: Soft delete - halaman sampah, restore, dan
    // hapus permanen.
    // =====================================================

    public function trashed()
    {
        $pengguna = User::onlyTrashed()
            ->with('pegawai.jabatan')
            ->orderBy('username')
            ->paginate(15);

        return view('master.pengguna.trashed', compact('pengguna'));
    }

    public function restore($uuid)
    {
        $pengguna = User::onlyTrashed()->where('uuid', $uuid)->firstOrFail();

        // Jaga-jaga: cegah restore akun yang usernamenya sudah "dipakai lagi"
        // oleh akun aktif lain (bisa terjadi kalau username sempat dipakai
        // ulang setelah akun lama di-soft-delete).
        $bentrok = User::where('username', $pengguna->username)
            ->where('id_user', '!=', $pengguna->id_user)
            ->exists();

        if ($bentrok) {
            return back()->with('gagal', 'Tidak bisa dipulihkan, username ini sudah dipakai akun lain yang masih aktif.');
        }

        $pengguna->restore();

        return back()->with('sukses', 'Akun pengguna berhasil dipulihkan.');
    }

    public function forceDelete($uuid)
    {
        $pengguna = User::onlyTrashed()->where('uuid', $uuid)->firstOrFail();
        $pengguna->forceDelete();

        return back()->with('sukses', 'Akun pengguna berhasil dihapus permanen.');
    }
}