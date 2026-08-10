<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use App\Models\Jurusan;
use App\Models\Pegawai;
use App\Models\Sekolah;
use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::with(['jabatan', 'unitKerja', 'user'])->orderBy('nama_lengkap')->paginate(15);
        return view('master.pegawai.index', compact('pegawai'));
    }

    public function create()
    {
        return view('master.pegawai.form', [
            'pegawai' => new Pegawai(),
            'jabatanList' => Jabatan::orderBy('nama_jabatan')->get(),
            'unitList' => UnitKerja::orderBy('nama_unit')->get(),
            'jurusanList' => Jurusan::orderBy('nama_jurusan')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);

        DB::transaction(function () use ($data, $request) {
            $pegawai = Pegawai::create([
                ...$data,
                'id_sekolah' => Sekolah::first()->id_sekolah,
                'status' => 'aktif',
            ]);

            if ($request->filled('username') && $request->filled('password')) {
                $request->validate([
                    'username' => 'unique:users,username',
                    'password' => 'min:6',
                    'role' => 'required|in:super_admin,admin_tu,kepala_sekolah,staff,guru,operator',
                ]);

                User::create([
                    'id_pegawai' => $pegawai->id_pegawai,
                    'username' => $request->username,
                    'password_hash' => Hash::make($request->password),
                    'role' => $request->role,
                    'status' => 'aktif',
                ]);
            }
        });

        return redirect()->route('pegawai.index')->with('sukses', 'Pegawai berhasil ditambahkan.');
    }

    public function edit(Pegawai $pegawai)
    {
        return view('master.pegawai.form', [
            'pegawai' => $pegawai,
            'jabatanList' => Jabatan::orderBy('nama_jabatan')->get(),
            'unitList' => UnitKerja::orderBy('nama_unit')->get(),
            'jurusanList' => Jurusan::orderBy('nama_jurusan')->get(),
        ]);
    }

    public function update(Request $request, Pegawai $pegawai)
    {
        $data = $this->validasi($request, $pegawai->id_pegawai);
        $pegawai->update($data);

        return redirect()->route('pegawai.index')->with('sukses', 'Data pegawai berhasil diperbarui.');
    }

    public function destroy(Pegawai $pegawai)
    {
        if ($pegawai->user) {
            return back()->with('gagal', 'Pegawai ini masih punya akun login, hapus akunnya dulu.');
        }

        $pegawai->delete();

        return redirect()->route('pegawai.index')->with('sukses', 'Pegawai berhasil dihapus.');
    }

    protected function validasi(Request $request, ?int $idPegawai = null): array
    {
        return $request->validate([
            'nip' => 'required|string|max:30|unique:pegawai,nip,'.$idPegawai.',id_pegawai',
            'nama_lengkap' => 'required|string|max:100',
            'gelar_depan' => 'nullable|string|max:20',
            'gelar_belakang' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:L,P',
            'id_jabatan' => 'nullable|exists:jabatan,id_jabatan',
            'id_unit' => 'nullable|exists:unit_kerja,id_unit',
            'id_jurusan' => 'nullable|exists:jurusan,id_jurusan',
            'pangkat_golongan' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
        ]);
    }
}
