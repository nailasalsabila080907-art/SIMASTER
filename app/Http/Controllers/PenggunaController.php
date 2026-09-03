<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        return view('master.pengguna.index', [
            'pengguna' => User::with('pegawai.jabatan')
                ->orderBy('username')
                ->paginate(15)
        ]);
    }

    public function create()
    {
        $pegawai = Pegawai::with('jabatan')
            ->whereDoesntHave('user')
            ->where('status', 'aktif')
            ->orderBy('nama_lengkap')
            ->get();

        return view('master.pengguna.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pegawai' => ['required', 'exists:pegawai,id_pegawai'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:super_admin,admin_tu,kepala_sekolah,staff,guru,operator'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ], [
            'id_pegawai.required' => 'Pegawai wajib dipilih.',
            'id_pegawai.exists' => 'Pegawai yang dipilih tidak ditemukan.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username tersebut sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if (User::where('id_pegawai', $data['id_pegawai'])->exists()) {
            return back()
                ->withInput()
                ->with('gagal', 'Pegawai tersebut sudah memiliki akun pengguna.');
        }

        User::create([
            'id_pegawai' => $data['id_pegawai'],
            'username' => $data['username'],
            'password_hash' => Hash::make($data['password']),
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        return redirect()
            ->route('pengguna.index')
            ->with('sukses', 'Akun pengguna berhasil ditambahkan dan dapat digunakan untuk login.');
    }

    public function edit(User $pengguna)
    {
        return view('master.pengguna.form', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50', 'unique:users,username,' . $pengguna->id_user . ',id_user'],
            'role' => ['required', 'in:super_admin,admin_tu,kepala_sekolah,staff,guru,operator'],
            'status' => ['required', 'in:aktif,nonaktif'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $pengguna->username = $data['username'];
        $pengguna->role = $data['role'];
        $pengguna->status = $data['status'];

        if (!empty($data['password'])) {
            $pengguna->password_hash = Hash::make($data['password']);
        }

        $pengguna->save();

        return redirect()
            ->route('pengguna.index')
            ->with('sukses', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        if ($pengguna->id_user === Auth::id()) {
            return back()->with('gagal', 'Akun yang sedang digunakan tidak boleh dihapus.');
        }

        $pengguna->delete();

        return redirect()
            ->route('pengguna.index')
            ->with('sukses', 'Akun pengguna berhasil dihapus.');
    }

    public function trashed()
    {
        $pengguna = User::onlyTrashed()
            ->with('pegawai.jabatan')
            ->orderBy('username')
            ->paginate(15);

        return view('master.pengguna.trashed', compact('pengguna'));
    }

    public function restore(string $uuid)
    {
        $pengguna = User::onlyTrashed()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $bentrok = User::where('username', $pengguna->username)
            ->where('id_user', '!=', $pengguna->id_user)
            ->exists();

        if ($bentrok) {
            return back()->with(
                'gagal',
                'Tidak bisa dipulihkan, username ini sudah dipakai akun lain yang masih aktif.'
            );
        }

        $pengguna->restore();

        return back()->with('sukses', 'Akun pengguna berhasil dipulihkan.');
    }

    public function forceDelete(string $uuid)
    {
        $pengguna = User::onlyTrashed()
            ->where('uuid', $uuid)
            ->firstOrFail();

        $pengguna->forceDelete();

        return back()->with('sukses', 'Akun pengguna berhasil dihapus permanen.');
    }
}