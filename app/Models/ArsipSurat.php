<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArsipSurat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'arsip_surat';
    protected $primaryKey = 'id_arsip';

    protected $fillable = [
        'tipe_surat', 'id_surat', 'lokasi_fisik', 'tahun_arsip',
        'tanggal_diarsipkan', 'diarsipkan_oleh',
    ];

    protected $casts = [
        'tanggal_diarsipkan' => 'datetime',
    ];

    public function pengarsip(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diarsipkan_oleh', 'id_user');
    }

    public function suratTerkait(): SuratMasuk|SuratKeluar|null
    {
        return $this->tipe_surat === 'masuk'
            ? SuratMasuk::find($this->id_surat)
            : SuratKeluar::find($this->id_surat);
    }
}
