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
        'id_user', 'aktivitas', 'modul', 'deskripsi', 'url', 'method',
        'ip_address', 'user_agent', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Helper cepat, bisa dipanggil manual dari mana saja kalau perlu catat sesuatu yang spesifik
    public static function catat(string $aktivitas, ?string $modul = null, ?string $deskripsi = null): self
    {
        $request = request();

        return self::create([
            'id_user' => Auth::id(),
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
