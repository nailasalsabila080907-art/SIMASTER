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

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (Auth::check() && $request->isMethod('GET') && ! $this->dikecualikan($request)) {
            LogAktivitas::create([
                'id_user' => Auth::id(),
                'aktivitas' => 'lihat_halaman',
                'modul' => $this->tebakModul($request),
                'deskripsi' => 'Membuka halaman: '.($request->route()?->getName() ?? $request->path()),
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
}
