<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogAktivitasSurat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'log_aktivitas_surat';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'tipe_surat', 'id_surat', 'id_user', 'aktivitas', 'keterangan',
        'ip_address', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Helper cepat buat nyatet aktivitas dari mana pun di kode,
    // mis. LogAktivitasSurat::catat('keluar', $surat->id_surat_keluar, 'mengajukan approval');
    public static function catat(string $tipeSurat, int $idSurat, string $aktivitas, ?string $keterangan = null): self
    {
        return self::create([
            'tipe_surat' => $tipeSurat,
            'id_surat' => $idSurat,
            'id_user' => auth()->id(),
            'aktivitas' => $aktivitas,
            'keterangan' => $keterangan,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}
