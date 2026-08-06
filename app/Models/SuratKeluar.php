<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'surat_keluar';
    protected $primaryKey = 'id_surat_keluar';

    protected $fillable = [
        'nomor_surat', 'id_template', 'id_kategori', 'id_klasifikasi', 'id_unit_pembuat',
        'id_surat_masuk_asal', 'perihal', 'tujuan', 'isi_surat', 'data_variabel',
        'tanggal_surat', 'sifat_surat', 'status', 'file_draft_path', 'file_final_path',
        'dibuat_oleh',
    ];

    protected $casts = [
        'data_variabel' => 'array',
        'tanggal_surat' => 'date',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplateSurat::class, 'id_template', 'id_template');
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'id_kategori', 'id_kategori');
    }

    public function klasifikasi(): BelongsTo
    {
        return $this->belongsTo(KlasifikasiArsip::class, 'id_klasifikasi', 'id_klasifikasi');
    }

    public function unitPembuat(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit_pembuat', 'id_unit');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh', 'id_user');
    }

    // Diisi jika surat ini dibuat sebagai tindak lanjut disposisi surat masuk (mis. Surat Tugas)
    public function suratMasukAsal(): BelongsTo
    {
        return $this->belongsTo(SuratMasuk::class, 'id_surat_masuk_asal', 'id_surat_masuk');
    }

    public function tembusan(): HasMany
    {
        return $this->hasMany(TembusanSuratKeluar::class, 'id_surat_keluar', 'id_surat_keluar');
    }

    public function approval(): HasMany
    {
        return $this->hasMany(ApprovalSuratKeluar::class, 'id_surat_keluar', 'id_surat_keluar')
            ->orderBy('urutan');
    }

    // Approval yang lagi ditunggu saat ini (urutan paling kecil yang belum diputuskan)
    public function approvalBerjalan(): ?ApprovalSuratKeluar
    {
        return $this->approval()->where('status', 'menunggu')->first();
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function sudahTerbitNomor(): bool
    {
        return ! is_null($this->nomor_surat);
    }
}