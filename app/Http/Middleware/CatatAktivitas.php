<?php

namespace App\Http\Middleware;

use App\Models\LogAktivitas;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatatAktivitas
{
    // Route/URL yang TIDAK perlu dicatat, biar log-nya nggak penuh sampah
    protected array $kecualikan = [
        'login', 'logout', // sudah dicatat khusus di LoginController
    ];

    // Kalau parameter route berupa model, coba tampilkan atribut ini (urut prioritas)
    // biar deskripsinya lebih manusiawi daripada cuma angka ID
    protected array $atributDeskriptif = [
        'nomor_surat', 'perihal', 'nama_lengkap', 'nama', 'judul', 'nama_kategori',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $request->isMethod('GET') && ! $this->dikecualikan($request)) {
            LogAktivitas::create([
                'id_user' => Auth::id(),
                'role' => Auth::user()?->role,
                'aktivitas' => 'lihat_halaman',
                'modul' => $this->tebakModul($request),
                'deskripsi' => $this->deskripsiHalaman($request),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => substr((string) $request->userAgent(), 0, 255),
                'created_at' => now(),
            ]);
        }

        return $response;
    }

    protected function dikecualikan(Request $request): bool
    {
        $routeName = $request->route()?->getName() ?? '';

        foreach ($this->kecualikan as $pola) {
            if (str_starts_with($routeName, $pola)) {
                return true;
            }
        }

        return false;
    }

    // Tebak nama modul dari segmen pertama URL, mis. /surat-keluar/5 -> "Surat Keluar"
    protected function tebakModul(Request $request): string
    {
        $segmen = $request->segment(1) ?? 'dashboard';

        return ucwords(str_replace('-', ' ', $segmen));
    }

    // Bangun deskripsi lengkap: nama halaman + detail parameter (ID atau atribut deskriptif)
    protected function deskripsiHalaman(Request $request): string
    {
        $label = $request->route()?->getName() ?? $request->path();
        $detail = $this->detailParameter($request);

        return 'Membuka halaman: '.$label.($detail ? " ({$detail})" : '');
    }

    // Ambil detail dari parameter route, misalnya SuratKeluar $suratKeluar -> "Perihal: Undangan Rapat"
    // Kalau tidak ada atribut deskriptif yang cocok, fallback ke primary key / nilai mentah.
    protected function detailParameter(Request $request): ?string
    {
        $route = $request->route();

        if (! $route) {
            return null;
        }

        $bagian = [];

        foreach ($route->parameters() as $nama => $nilai) {
            if (is_object($nilai)) {
                $identitas = method_exists($nilai, 'getKey')
                    ? $nilai->getKey()
                    : (string) $nilai;

                foreach ($this->atributDeskriptif as $atribut) {
                    if (isset($nilai->{$atribut}) && $nilai->{$atribut} !== null) {
                        $identitas = $nilai->{$atribut};
                        break;
                    }
                }

                $bagian[] = 'ID: '.$identitas;
            } else {
                $bagian[] = ucfirst($nama).': '.$nilai;
            }
        }

        return $bagian ? implode(', ', $bagian) : null;
    }
}