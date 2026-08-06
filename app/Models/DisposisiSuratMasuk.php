<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisposisiSuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'disposisi_surat_masuk';
    protected $primaryKey = 'id_disposisi';

    protected $fillable = [
        'id_surat_masuk', 'id_disposisi_asal', 'dari_pegawai', 'ke_pegawai',
        'ke_unit', 'ke_jurusan', 'instruksi', 'catatan', 'status',
        'tanggal_disposisi', 'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_disposisi' => 'datetime',
        'tanggal_selesai' => 'datetime',
    ];

    public function suratMasuk(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'id_surat_masuk', 'id_surat_masuk');
    }

    public function disposisiAsal(): BelongsTo
    {
        return $this->belongsTo(DisposisiSuratMasuk::class, 'id_disposisi_asal', 'id_disposisi');
    }

    // Disposisi lanjutan yang diteruskan dari disposisi ini
    public function diteruskanKe(): HasMany
    {
        return $this->hasMany(DisposisiSuratMasuk::class, 'id_disposisi_asal', 'id_disposisi');
    }

    public function pemberiDisposisi(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'dari_pegawai', 'id_pegawai');
    }

    public function penerimaPegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'ke_pegawai', 'id_pegawai');
    }

    public function penerimaUnit(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'ke_unit', 'id_unit');
    }

    public function penerimaJurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'ke_jurusan', 'id_jurusan');
    }

    // Nama tujuan disposisi, dari salah satu ke_pegawai / ke_unit / ke_jurusan yang terisi
    public function getTujuanLabelAttribute(): string
    {
        return $this->penerimaPegawai?->nama_lengkap
            ?? $this->penerimaUnit?->nama_unit
            ?? $this->penerimaJurusan?->nama_jurusan
            ?? '-';
    }
}
