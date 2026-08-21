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

   
public const TIPE_MASUK = 'masuk';
public const TIPE_KELUAR = 'keluar';

public const AKSI_DIBUAT = 'dibuat';
public const AKSI_DIEDIT = 'diedit';
public const AKSI_DIAJUKAN = 'diajukan';
public const AKSI_DISPOSISI = 'disposisi';
public const AKSI_TINDAK_LANJUT = 'tindak_lanjut';
public const AKSI_DISPOSISI_SELESAI = 'disposisi_selesai';
public const AKSI_SELESAI = 'selesai';
public const AKSI_APPROVE = 'approve';
public const AKSI_TOLAK = 'tolak';
public const AKSI_TERBIT = 'terbit';
public const AKSI_ARSIP = 'arsip';
public const AKSI_HAPUS = 'dihapus';
public const AKSI_CETAK = 'cetak_pdf';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
        public function getSuratAttribute()
    {
        return $this->tipe_surat === self::TIPE_MASUK
            ? \App\Models\SuratMasuk::find($this->id_surat)
            : \App\Models\SuratKeluar::find($this->id_surat);
    }

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