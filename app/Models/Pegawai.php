<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $primaryKey = 'id_pegawai';

    protected $fillable = [
        'id_sekolah', 'id_unit', 'id_jabatan', 'id_jurusan', 'nip', 'nama_lengkap',
        'gelar_depan', 'gelar_belakang', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
        'pangkat_golongan', 'no_hp', 'email', 'tanda_tangan_path', 'status', 'foto_path',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'id_sekolah', 'id_sekolah');
    }

    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(UnitKerja::class, 'id_unit', 'id_unit');
    }

    public function jabatan(): BelongsTo
    {
        return $this->belongsTo(Jabatan::class, 'id_jabatan', 'id_jabatan');
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan', 'id_jurusan');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_pegawai', 'id_pegawai');
    }

    public function approvalSuratKeluar(): HasMany
    {
        return $this->hasMany(ApprovalSuratKeluar::class, 'id_pegawai_pemberi_approval', 'id_pegawai');
    }

    // Nama lengkap + gelar, dipakai saat generate surat
    public function getNamaBergelarAttribute(): string
    {
        return trim("{$this->gelar_depan} {$this->nama_lengkap}, {$this->gelar_belakang}", ' ,');
    }
}
