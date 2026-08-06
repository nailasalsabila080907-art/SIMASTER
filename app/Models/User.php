<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'id_pegawai', 'username', 'password_hash', 'role', 'last_login', 'status',
    ];

    protected $hidden = [
        'password_hash', 'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'id_pegawai', 'id_pegawai');
    }

    public function suratKeluarDibuat(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'dibuat_oleh', 'id_user');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class, 'id_user', 'id_user');
    }

    // Laravel Auth defaultnya cari kolom "password" - kita override ke "password_hash"
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // Laravel Auth defaultnya login pakai "email" - kita pakai "username"
    public function username(): string
    {
        return 'username';
    }

    public function isRole(string $role): bool
    {
        return $this->role === $role;
    }
}