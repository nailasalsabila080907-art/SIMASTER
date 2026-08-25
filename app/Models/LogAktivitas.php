<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class LogAktivitas extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'log_aktivitas';
    protected $primaryKey = 'id_log_aktivitas';

    protected $fillable = [
        'id_user', 'role', 'aktivitas', 'modul', 'deskripsi', 'url', 'method',
        'ip_address', 'user_agent', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Parse user_agent selengkap-lengkapnya pakai jenssegers/agent:
    // browser + versi, OS + versi, dan tipe device (Desktop/Mobile/Tablet/Bot)
    public function getPerangkatAttribute(): string
    {
        $ua = (string) $this->user_agent;

        if ($ua === '') {
            return '-';
        }

        $agent = new \Jenssegers\Agent\Agent();
        $agent->setUserAgent($ua);

        $browser = $agent->browser() ?: 'Unknown Browser';
        $browserVersion = $agent->version($browser) ?: '';

        $platform = $agent->platform() ?: 'Unknown OS';
        $platformVersion = $agent->version($platform) ?: '';

        $tipeDevice = match (true) {
            $agent->isRobot() => 'Bot ('.$agent->robot().')',
            $agent->isTablet() => 'Tablet',
            $agent->isMobile() => 'Mobile',
            default => 'Desktop',
        };

        $browserLabel = trim("{$browser} {$browserVersion}");
        $platformLabel = trim("{$platform} {$platformVersion}");

        return "{$browserLabel} · {$platformLabel} · {$tipeDevice}";
    }

    // Helper cepat, bisa dipanggil manual dari mana saja kalau perlu catat sesuatu yang spesifik
    public static function catat(string $aktivitas, ?string $modul = null, ?string $deskripsi = null): self
    {
        $request = request();

        return self::create([
            'id_user' => Auth::id(),
            'role' => Auth::user()?->role,
            'aktivitas' => $aktivitas,
            'modul' => $modul,
            'deskripsi' => $deskripsi,
            'url' => $request?->fullUrl(),
            'method' => $request?->method(),
            'ip_address' => $request?->ip(),
            'user_agent' => substr((string) $request?->userAgent(), 0, 255),
            'created_at' => now(),
        ]);
    }
}