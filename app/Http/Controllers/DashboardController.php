<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSuratKeluar;
use App\Models\KategoriSurat;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load('pegawai');
        $role = $user->role;
        $canSeeAll = in_array($role, ['admin_tu', 'super_admin', 'kepala_sekolah'], true);

        $data = [
            'user' => $user,
            'notifikasiBelumDibaca' => Notifikasi::where('id_user', $user->id_user)->where('sudah_dibaca', false)->count(),
            'notifikasiTerbaru' => Notifikasi::where('id_user', $user->id_user)->latest('created_at')->limit(5)->get(),
            'aktivitasTerbaru' => LogAktivitas::where('id_user', $user->id_user)->latest('created_at')->limit(8)->get(),
            'canSeeAll' => $canSeeAll,
        ];

        $suratKeluar = $canSeeAll ? SuratKeluar::query() : SuratKeluar::where('dibuat_oleh', $user->id_user);
        $suratMasuk = $canSeeAll ? SuratMasuk::query() : SuratMasuk::where('diterima_oleh', $user->id_user);

        $data['statistik'] = [
            ['label' => $canSeeAll ? 'Surat Masuk' : 'Surat Masuk Saya', 'nilai' => (clone $suratMasuk)->count(), 'icon' => '↓'],
            ['label' => $canSeeAll ? 'Surat Keluar' : 'Surat Keluar Saya', 'nilai' => (clone $suratKeluar)->count(), 'icon' => '↑'],
            ['label' => 'Menunggu Approval', 'nilai' => $canSeeAll
                ? SuratKeluar::where('status', 'diajukan')->count()
                : SuratKeluar::where('dibuat_oleh', $user->id_user)->where('status', 'diajukan')->count(), 'icon' => '◷'],
            ['label' => 'Sudah Terbit', 'nilai' => (clone $suratKeluar)->where('status', 'terkirim')->count(), 'icon' => '✓'],
            ['label' => 'Arsip Tahun Ini', 'nilai' => (clone $suratKeluar)->where('status', 'diarsipkan')->whereYear('tanggal_surat', now()->year)->count(), 'icon' => '▣'],
        ];

        $bulan = collect(range(5, 0))->map(function ($i) {
            $date = now()->startOfMonth()->subMonths($i);
            return ['label' => $date->translatedFormat('M'), 'date' => $date];
        });

        $data['grafik'] = $bulan->map(function ($item) use ($canSeeAll, $user) {
            $keluar = SuratKeluar::whereMonth('created_at', $item['date']->month)->whereYear('created_at', $item['date']->year);
            $masuk = SuratMasuk::whereMonth('created_at', $item['date']->month)->whereYear('created_at', $item['date']->year);
            if (! $canSeeAll) {
                $keluar->where('dibuat_oleh', $user->id_user);
                $masuk->where('diterima_oleh', $user->id_user);
            }
            return ['label' => $item['label'], 'masuk' => $masuk->count(), 'keluar' => $keluar->count()];
        });

            $kategoriQuery = KategoriSurat::where('jenis', 'keluar')
        ->withCount('templateSurat');

         $kategoriList = $kategoriQuery
        ->orderByDesc('template_surat_count')
        ->limit(6)
        ->get();
        $data['ringkasanKategori'] = $kategoriList->map(function ($kategori) use ($canSeeAll, $user) {
            $query = SuratKeluar::where('id_kategori', $kategori->id_kategori);
            if (! $canSeeAll) $query->where('dibuat_oleh', $user->id_user);
            return ['nama' => $kategori->nama_kategori, 'jumlah' => $query->count()];
        });

        if ($role === 'kepala_sekolah') {
            $data['approvalSaya'] = ApprovalSuratKeluar::where('id_pegawai_pemberi_approval', $user->pegawai?->id_pegawai)->where('status', 'menunggu')->count();
        } else {
            $data['approvalSaya'] = 0;
        }

        return view('dashboard', $data);
    }
}
