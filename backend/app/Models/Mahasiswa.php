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

    /**
     * Scope untuk filter mahasiswa yang memiliki portofolio public
     */
    public function scopeWithPublicPortfolios($query)
    {
        return $query->whereHas('portofolios', function($q) {
            $q->where('is_public', true);
        });
    }

    /**
     * Scope untuk search mahasiswa by keyword (NIM, deskripsi, nama user, atau nama portofolio)
     */
    public function scopeSearchable($query, $keyword)
    {
        if (!$keyword) {
            return $query;
        }

        return $query->where(function($q) use ($keyword) {
            $q->where('nim', 'like', "%{$keyword}%")
              ->orWhere('deskripsi_diri', 'like', "%{$keyword}%")
              ->orWhereHas('user', function($userQuery) use ($keyword) {
                  $userQuery->where('name', 'like', "%{$keyword}%");
              })
              ->orWhereHas('portofolios', function($portfolioQuery) use ($keyword) {
                  $portfolioQuery->where('is_public', true)
                      ->where(function($pq) use ($keyword) {
                          $pq->where('nama', 'like', "%{$keyword}%")
                             ->orWhere('deskripsi', 'like', "%{$keyword}%");
                      });
              });
        });
    }

    /**
     * Scope untuk filter by jurusan
     */
    public function scopeByJurusan($query, $jurusan)
    {
        if ($jurusan) {
            return $query->where('jurusan', 'like', "%{$jurusan}%");
        }
        return $query;
    }

    /**
     * Scope untuk filter by fakultas
     */
    public function scopeByFakultas($query, $fakultas)
    {
        if ($fakultas) {
            return $query->where('fakultas', 'like', "%{$fakultas}%");
        }
        return $query;
    }

    /**
     * Scope untuk filter by universitas
     */
    public function scopeByUniversitas($query, $universitas)
    {
        if ($universitas) {
            return $query->where('universitas', 'like', "%{$universitas}%");
        }
        return $query;
    }

    /**
     * Scope untuk filter by skill
     */
    public function scopeBySkill($query, $skill)
    {
        if (!$skill) {
            return $query;
        }

        return $query->whereHas('portofolios', function($q) use ($skill) {
            $q->where('is_public', true)
              ->whereHas('skills', function($skillQuery) use ($skill) {
                  $skillQuery->where('nama', 'like', "%{$skill}%");
              });
        });
    }

    /**
     * Query untuk search students dengan berbagai filter
     */
    public static function searchStudents($filters = [])
    {
        $query = static::with([
                'user:id,name,email',
                'portofolios' => function($q) {
                    $q->where('is_public', true)
                      ->select('id', 'mahasiswa_id', 'nama', 'bidang', 'deskripsi', 'public_link', 'is_public');
                }
            ])
            ->select('id', 'user_id', 'nim', 'jurusan', 'fakultas', 'universitas', 'deskripsi_diri')
            ->withPublicPortfolios();

        // Apply filters
        if (isset($filters['keyword'])) {
            $query->searchable($filters['keyword']);
        }

        if (isset($filters['skill'])) {
            $query->bySkill($filters['skill']);
        }

        if (isset($filters['jurusan'])) {
            $query->byJurusan($filters['jurusan']);
        }

        if (isset($filters['fakultas'])) {
            $query->byFakultas($filters['fakultas']);
        }

        if (isset($filters['universitas'])) {
            $query->byUniversitas($filters['universitas']);
        }

        return $query;
    }

    /**
     * Query untuk mendapatkan mahasiswa dengan portofolio public
     */
    public static function withPublicPortfolioData($id)
    {
        return static::with([
                'user',
                'portofolios' => function($q) {
                    $q->where('is_public', true);
                },
                'projects',
                'skills',
                'certificates',
                'experiences'
            ])
            ->where('id', $id)
            ->withPublicPortfolios()
            ->first();
    }
}
