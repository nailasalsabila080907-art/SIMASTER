<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Jabatan extends Model
{
    use HasUuids;
    use HasFactory;

    protected $table = 'jabatan';
    protected $primaryKey = 'id_jabatan';

    protected $fillable = ['nama_jabatan', 'level_jabatan', 'keterangan'];

    public function pegawai(): HasMany
    {
        return $this->hasMany(Pegawai::class, 'id_jabatan', 'id_jabatan');
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
