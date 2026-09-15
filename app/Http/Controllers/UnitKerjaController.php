<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Sekolah;
use App\Models\UnitKerja;
use App\Models\UnitKerjaAdmin;
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
        // Kandidat admin unit TIDAK dibatasi harus satu id_unit dengan unit ini -
        // di struktur sekolah, "unit kepegawaian" pegawai (buat data HR) bisa beda
        // dengan unit yang dia administrasikan (contoh: Wakil Kepsek Bidang
        // Kesiswaan data kepegawaiannya ada di unit TU, tapi dialah yang pegang
        // akun unit Kesiswaan). Jadi tampilkan semua pegawai aktif yang sudah
        // punya akun & belum jadi admin aktif unit ini, dari unit mana pun.
        $calonAdmin = Pegawai::where('status', 'aktif')
            ->whereHas('user')
            ->whereDoesntHave('user.unitDiadmini', function ($q) use ($unitKerja) {
                $q->where('id_unit', $unitKerja->id_unit)->where('status', 'aktif');
            })
            ->with(['user', 'unitKerja'])
            ->orderBy('nama_lengkap')
            ->get();

        $adminUnit = $unitKerja->admin()->with('user.pegawai')->orderByDesc('status')->orderBy('peran')->get();

        return view('master.unit-kerja.form', [
            'unit' => $unitKerja,
            'sekolah' => Sekolah::firstOrFail(),
            'calonAdmin' => $calonAdmin,
            'adminUnit' => $adminUnit,
        ]);
    }

    // Menunjuk seorang pegawai (yang sudah punya akun) sebagai admin unit ini.
    public function tambahAdmin(Request $request, UnitKerja $unitKerja)
    {
        $data = $request->validate([
            'id_user' => ['required', 'exists:users,id_user'],
            'peran' => ['required', 'in:utama,cadangan'],
        ]);

        UnitKerjaAdmin::updateOrCreate(
            ['id_unit' => $unitKerja->id_unit, 'id_user' => $data['id_user']],
            ['peran' => $data['peran'], 'status' => 'aktif']
        );

        return back()->with('sukses', 'Admin unit berhasil ditambahkan.');
    }

    // Menonaktifkan admin unit (bukan hapus, supaya riwayat tetap ada)
    public function nonaktifkanAdmin(UnitKerja $unitKerja, UnitKerjaAdmin $admin)
    {
        abort_unless($admin->id_unit === $unitKerja->id_unit, 404);
        $admin->update(['status' => 'nonaktif']);

        return back()->with('sukses', 'Admin unit berhasil dinonaktifkan.');
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