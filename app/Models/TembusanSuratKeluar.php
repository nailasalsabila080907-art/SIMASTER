<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TembusanSuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'tembusan_surat_keluar';
    protected $primaryKey = 'id_tembusan';

    protected $fillable = ['id_surat_keluar', 'nama_tujuan'];

    public function suratKeluar(): BelongsTo
    {
        return $this->belongsTo(SuratKeluar::class, 'id_surat_keluar', 'id_surat_keluar');
    }
}
