<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'id_user', 'tipe_surat', 'id_surat', 'judul', 'pesan', 'sudah_dibaca', 'created_at',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Helper cepat buat kirim notifikasi ke seorang user
    public static function kirim(
    int $idUser,
    string $tipeSurat,
    string $idSurat,
    string $judul,
    string $pesan
): self {
    return self::create([
        'id_user' => $idUser,
        'tipe_surat' => $tipeSurat,
        'id_surat' => $idSurat,
        'judul' => $judul,
        'pesan' => $pesan,
        'sudah_dibaca' => false,
        'created_at' => now(),
    ]);
}

    public function tandaiDibaca(): void
    {
        $this->update(['sudah_dibaca' => true]);
    }
}
