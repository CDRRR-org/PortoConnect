<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mahasiswa extends Model
{
    protected $fillable = [
        'user_id',
        'nim',
        'jurusan',
        'fakultas',
        'universitas',
        'deskripsi_diri',
        'foto_profil',
        'no_telp',
        'alamat',
        'tanggal_lahir',
        'linkedin',
        'github',
        'website',
        'is_verified',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_verified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function portofolio(): HasOne
    {
        return $this->hasOne(Portofolio::class);
    }

    public function portofolios(): HasMany
    {
        return $this->hasMany(Portofolio::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }
}
