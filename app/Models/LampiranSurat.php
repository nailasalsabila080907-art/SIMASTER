<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LampiranSurat extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'lampiran_surat';
    protected $primaryKey = 'id_lampiran';

    protected $fillable = [
        'tipe_surat', 'id_surat', 'nama_file', 'file_path', 'ukuran_file',
        'uploaded_by', 'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id_user');
    }

    // id_surat merujuk ke surat_masuk atau surat_keluar tergantung tipe_surat.
    // Method ini otomatis ambil model yang benar tanpa perlu cek manual.
    public function suratTerkait(): SuratMasuk|SuratKeluar|null
    {
        return $this->tipe_surat === 'masuk'
            ? SuratMasuk::find($this->id_surat)
            : SuratKeluar::find($this->id_surat);
    }
}
