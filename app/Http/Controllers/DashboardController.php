<?php

namespace App\Http\Controllers;

use App\Models\ApprovalSuratKeluar;
use App\Models\KategoriSurat;
use App\Models\LogAktivitas;
use App\Models\Notifikasi;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user()->load('pegawai');
        $role = $user->role;

        $canSeeAll = in_array(
            $role,
            ['admin_tu', 'super_admin', 'kepala_sekolah'],
            true
        );


        $data = [
            'user' => $user,

            'notifikasiBelumDibaca' => Notifikasi::where('id_user', $user->id_user)
                ->where('sudah_dibaca', false)
                ->count(),

            'notifikasiTerbaru' => Notifikasi::where('id_user', $user->id_user)
                ->latest('created_at')
                ->limit(5)
                ->get(),

            'aktivitasTerbaru' => LogAktivitas::where('id_user', $user->id_user)
                ->latest('created_at')
                ->limit(8)
                ->get(),

            'canSeeAll' => $canSeeAll,
        ];


        $suratKeluar = $canSeeAll
            ? SuratKeluar::query()
            : SuratKeluar::where('dibuat_oleh', $user->id_user);

        $suratMasuk = $canSeeAll
            ? SuratMasuk::query()
            : SuratMasuk::where('diterima_oleh', $user->id_user);

        $data['statistik'] = [

            [
                'label' => $canSeeAll
                    ? 'Surat Masuk'
                    : 'Surat Masuk Saya',

                'nilai' => (clone $suratMasuk)->count(),

                'icon' => '↓',
            ],

            [
                'label' => $canSeeAll
                    ? 'Surat Keluar'
                    : 'Surat Keluar Saya',

                'nilai' => (clone $suratKeluar)->count(),

                'icon' => '↑',
            ],

            [
                'label' => 'Menunggu Approval',

                'nilai' => $canSeeAll
                    ? SuratKeluar::where('status', 'diajukan')->count()
                    : SuratKeluar::where('dibuat_oleh', $user->id_user)
                        ->where('status', 'diajukan')
                        ->count(),

                'icon' => '◷',
            ],

            [
                'label' => 'Sudah Terbit',

                'nilai' => (clone $suratKeluar)
                    ->whereIn('status', ['terkirim', 'diarsipkan'])
                    ->count(),

                'icon' => '✓',
            ],

            [
                'label' => 'Arsip Tahun Ini',

                'nilai' => (clone $suratKeluar)
                    ->where('status', 'diarsipkan')
                    ->whereYear('tanggal_surat', now()->year)
                    ->count(),

                'icon' => '▣',
            ],

        ];
        $modeGrafik = $request->input('mode_grafik', 'bulan');

            if (! in_array($modeGrafik, ['minggu', 'bulan'], true)) {
                $modeGrafik = 'bulan';

        }

        try {
            $tanggalMulai = $request->filled('tanggal_mulai')
                ? Carbon::parse($request->tanggal_mulai)->startOfDay()
                : now()->startOfYear()->startOfDay();

            $tanggalAkhir = $request->filled('tanggal_akhir')
                ? Carbon::parse($request->tanggal_akhir)->endOfDay()
                : now()->endOfDay();

        } catch (\Throwable $e) {

            $tanggalMulai = now()->startOfYear()->startOfDay();
            $tanggalAkhir = now()->endOfDay();

        }

        if ($tanggalMulai->greaterThan($tanggalAkhir)) {
            [$tanggalMulai, $tanggalAkhir] = [
                $tanggalAkhir->copy()->startOfDay(),
                $tanggalMulai->copy()->endOfDay(),
            ];
        }


        $queryKeluar = SuratKeluar::whereBetween(
            'created_at',
            [$tanggalMulai, $tanggalAkhir]
        );

        $queryMasuk = SuratMasuk::whereBetween(
            'created_at',
            [$tanggalMulai, $tanggalAkhir]
        );


        if (! $canSeeAll) {

            $queryKeluar->where(
                'dibuat_oleh',
                $user->id_user
            );

            $queryMasuk->where(
                'diterima_oleh',
                $user->id_user
            );

        }


        $dataKeluar = $queryKeluar
            ->orderBy('created_at')
            ->get([
                'created_at',
            ]);

        $dataMasuk = $queryMasuk
            ->orderBy('created_at')
            ->get([
                'created_at',
            ]);

        $grafik = collect();


        if ($modeGrafik === 'minggu') {

            $cursor = $tanggalMulai->copy()->startOfWeek(
                Carbon::MONDAY
            );

            $akhirMinggu = $tanggalAkhir->copy();

            while ($cursor->lte($akhirMinggu)) {

                $awalMinggu = $cursor->copy()->startOfWeek(
                    Carbon::MONDAY
                );

                $akhirMingguItem = $cursor->copy()->endOfWeek(
                    Carbon::SUNDAY
                );

                $jumlahMasuk = $dataMasuk
                    ->filter(fn ($item) =>
                        $item->created_at->between(
                            $awalMinggu,
                            $akhirMingguItem
                        )
                    )
                    ->count();

                $jumlahKeluar = $dataKeluar
                    ->filter(fn ($item) =>
                        $item->created_at->between(
                            $awalMinggu,
                            $akhirMingguItem
                        )
                    )
                    ->count();

                $grafik->push([
                    'label' =>
                        $awalMinggu->translatedFormat('d M')
                        . ' - '
                        . $akhirMingguItem->translatedFormat('d M'),

                    'masuk' => $jumlahMasuk,
                    'keluar' => $jumlahKeluar,
                ]);

                $cursor->addWeek();
            }
        }


            else {
            $cursor = $tanggalMulai->copy()->startOfMonth();
            while ($cursor->lte($tanggalAkhir)) {

                $awalBulan = $cursor->copy()->startOfMonth();
                $akhirBulan = $cursor->copy()->endOfMonth();

                $jumlahMasuk = $dataMasuk
                    ->filter(fn ($item) =>
                        $item->created_at->between(
                            $awalBulan,
                            $akhirBulan
                        )
                    )
                    ->count();

                $jumlahKeluar = $dataKeluar
                    ->filter(fn ($item) =>
                        $item->created_at->between(
                            $awalBulan,
                            $akhirBulan
                        )
                    )
                    ->count();

                $grafik->push([
                    'label' => $awalBulan->translatedFormat('M Y'),
                    'masuk' => $jumlahMasuk,
                    'keluar' => $jumlahKeluar,
                ]);

                $cursor->addMonth();
            }
        }

        $data['grafik'] = $grafik;

        $data['modeGrafik'] = $modeGrafik;

        $data['tanggalMulai'] = $tanggalMulai->format('Y-m-d');

        $data['tanggalAkhir'] = $tanggalAkhir->format('Y-m-d');


        $kategoriQuery = KategoriSurat::where(
            'jenis',
            'keluar'
        )
            ->withCount('templateSurat');

        $kategoriList = $kategoriQuery
            ->orderByDesc('template_surat_count')
            ->limit(6)
            ->get();

        $data['ringkasanKategori'] = $kategoriList->map(
            function ($kategori) use ($canSeeAll, $user) {

                $query = SuratKeluar::where(
                    'id_kategori',
                    $kategori->id_kategori
                );

                if (! $canSeeAll) {
                    $query->where(
                        'dibuat_oleh',
                        $user->id_user
                    );
                }

                return [
                    'nama' => $kategori->nama_kategori,
                    'jumlah' => $query->count(),
                ];
            }
        );

        if ($role === 'kepala_sekolah') {

            $data['approvalSaya'] = ApprovalSuratKeluar::where(
                'id_pegawai_pemberi_approval',
                $user->pegawai?->id_pegawai
            )
                ->where('status', 'menunggu')
                ->count();

        } else {

            $data['approvalSaya'] = 0;

        }

        return view(
            'dashboard',
            $data
        );
    }
}