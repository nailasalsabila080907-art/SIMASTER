<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalSuratKeluar extends Model
{
    use HasFactory;

    protected $table = 'approval_surat_keluar';
    protected $primaryKey = 'id_approval';

    protected $fillable = [
        'id_surat_keluar', 'id_pegawai_pemberi_approval', 'urutan',
        'status', 'catatan', 'tanggal_approval',
    ];

    protected $casts = [
        'tanggal_approval' => 'datetime',
    ];

    public function suratKeluar(): BelongsTo
    {
        return $this->belongsTo(SuratKeluar::class, 'id_surat_keluar', 'id_surat_keluar');
    }

    public function pegawaiPemberiApproval(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai_pemberi_approval', 'id_pegawai');
    }

    public function setujui(?string $catatan = null): void
    {
        $this->update([
            'status' => 'disetujui',
            'catatan' => $catatan,
            'tanggal_approval' => now(),
        ]);
    }

    public function tolak(string $catatan): void
    {
        $this->update([
            'status' => 'ditolak',
            'catatan' => $catatan,
            'tanggal_approval' => now(),
        ]);
    }
}
