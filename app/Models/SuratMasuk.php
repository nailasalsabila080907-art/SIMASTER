<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratMasuk extends Model
{
    use HasUuids;
    use SoftDeletes;
    use HasFactory;

    protected $table = 'surat_masuk';
    protected $primaryKey = 'id_surat_masuk';

    protected $fillable = [
        'nomor_surat_masuk', 'nomor_surat_asal', 'asal_instansi', 'id_kategori',
        'id_klasifikasi', 'perihal', 'tanggal_surat', 'tanggal_diterima',
        'sifat_surat', 'file_scan_path', 'status', 'diterima_oleh',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_diterima' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'id_kategori', 'id_kategori');
    }

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'id_klasifikasi', 'id_klasifikasi');
    }

    public function penerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterima_oleh', 'id_user');
    }

    public function disposisi(): HasMany
    {
        return $this->hasMany(DisposisiSuratMasuk::class, 'id_surat_masuk', 'id_surat_masuk');
    }

    // Surat keluar (mis. Surat Tugas) yang dibuat sebagai tindak lanjut surat masuk ini
    public function suratKeluarTerkait(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'id_surat_masuk_asal', 'id_surat_masuk');
    }

    // Disposisi paling awal (root), belum diteruskan dari disposisi lain
    public function disposisiAwal(): HasMany
    {
        return $this->disposisi()->whereNull('id_disposisi_asal');
    }
     public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
