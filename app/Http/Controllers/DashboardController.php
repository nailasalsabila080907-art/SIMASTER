<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSuratKeluar;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('pegawai');
        $role = $user->role;

        $data = [
            'user' => $user,
            'notifikasiBelumDibaca' => Notifikasi::where('id_user', $user->id_user)
                ->where('sudah_dibaca', false)->count(),
            'aktivitasTerbaru' => LogAktivitas::where('id_user', $user->id_user)
                ->latest('created_at')->limit(5)->get(),
        ];

        if (in_array($role, ['admin_tu', 'staff', 'super_admin'])) {
            $data['statistik'] = [
                ['label' => 'Surat Keluar - Draft', 'nilai' => SuratKeluar::where('status', 'draft')->count()],
                ['label' => 'Menunggu Approval', 'nilai' => SuratKeluar::where('status', 'diajukan')->count()],
                ['label' => 'Surat Keluar Terbit', 'nilai' => SuratKeluar::where('status', 'terkirim')->count()],
                ['label' => 'Surat Masuk Baru', 'nilai' => SuratMasuk::where('status', 'baru')->count()],
            ];
        } elseif ($role === 'kepala_sekolah') {
            $pegawaiId = $user->pegawai?->id_pegawai;
            $data['statistik'] = [
                ['label' => 'Menunggu Persetujuan Saya', 'nilai' => ApprovalSuratKeluar::where('id_pegawai_pemberi_approval', $pegawaiId)->where('status', 'menunggu')->count()],
                ['label' => 'Surat Masuk Perlu Disposisi', 'nilai' => SuratMasuk::where('status', 'baru')->count()],
                ['label' => 'Surat Keluar Terbit Bulan Ini', 'nilai' => SuratKeluar::where('status', 'terkirim')->whereMonth('tanggal_surat', now()->month)->count()],
            ];
        } else { // guru dan role lain
            $data['statistik'] = [
                ['label' => 'Surat Saya - Draft', 'nilai' => SuratKeluar::where('dibuat_oleh', $user->id_user)->where('status', 'draft')->count()],
                ['label' => 'Sedang Diajukan', 'nilai' => SuratKeluar::where('dibuat_oleh', $user->id_user)->where('status', 'diajukan')->count()],
                ['label' => 'Sudah Terbit', 'nilai' => SuratKeluar::where('dibuat_oleh', $user->id_user)->where('status', 'terkirim')->count()],
            ];
        }

        return view('dashboard', $data);
    }
}
