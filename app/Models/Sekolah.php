<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sekolah extends Model
{
    use HasFactory;

    protected $table = 'sekolah';
    protected $primaryKey = 'id_sekolah';

    protected $fillable = [
        'npsn', 'kode_surat', 'nama_sekolah', 'alamat', 'kota', 'provinsi',
        'kode_pos', 'telepon', 'email', 'website', 'logo_path', 'kop_surat_path',
        'nama_kepala_sekolah', 'nip_kepala_sekolah',
    ];

    public function unitKerja(): HasMany
    {
        return $this->hasMany(UnitKerja::class, 'id_sekolah', 'id_sekolah');
    }

    public function jurusan(): HasMany
    {
        return $this->hasMany(Jurusan::class, 'id_sekolah', 'id_sekolah');
    }

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'id_sekolah', 'id_sekolah');
    }
}
