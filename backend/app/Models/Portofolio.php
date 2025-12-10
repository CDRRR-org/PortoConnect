<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Portofolio extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'nama',
        'bidang', // backend, frontend, fullstack, QATester
        'education', // JSON atau text
        'language', // JSON atau text
        'deskripsi', // Deskripsi portofolio
        'public_link',
        'pdf_path',
        'is_public',
        'custom_css',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portofolio) {
            if (empty($portofolio->public_link)) {
                $portofolio->public_link = Str::random(32);
            }
        });
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Get all public portfolios with relationships
     * @param string|null $bidang Filter by bidang (backend, frontend, fullstack, QATester)
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPublicPortfolios($bidang = null)
    {
        $query = static::with([
            'mahasiswa.user:id,name,email',
            'skills:id,portofolio_id,nama'
        ])
        ->where('is_public', true);

        if ($bidang) {
            $query->where('bidang', $bidang);
        }

        return $query->get();
    }

    /**
     * Get all public portfolios by mahasiswa ID
     * @param int $mahasiswaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getPublicPortfoliosByMahasiswa($mahasiswaId)
    {
        return static::with([
            'mahasiswa.user:id,name,email',
            'skills:id,portofolio_id,nama',
            'projects',
            'certificates',
            'experiences'
        ])
        ->where('mahasiswa_id', $mahasiswaId)
        ->where('is_public', true)
        ->get();
    }

    /**
     * Find portfolio by public link
     * @param string $publicLink
     * @return Portofolio|null
     */
    public static function findByPublicLink($publicLink)
    {
        return static::with([
            'mahasiswa.user',
            'skills',
            'projects',
            'certificates',
            'experiences'
        ])
        ->where('public_link', $publicLink)
        ->where('is_public', true)
        ->first();
    }

    /**
     * Scope untuk filter by bidang
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $bidang
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByBidang($query, $bidang = null)
    {
        if ($bidang) {
            return $query->where('bidang', $bidang);
        }
        return $query;
    }

    /**
     * Get portfolios by user ID (optimized with join)
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getByUserId($userId)
    {
        return static::join('mahasiswas', 'portofolios.mahasiswa_id', '=', 'mahasiswas.id')
            ->where('mahasiswas.user_id', $userId)
            ->select('portofolios.id', 'portofolios.mahasiswa_id', 'portofolios.nama', 'portofolios.bidang', 
                     'portofolios.deskripsi', 'portofolios.public_link', 'portofolios.is_public', 
                     'portofolios.created_at', 'portofolios.updated_at')
            ->orderBy('portofolios.created_at', 'desc')
            ->get()
            ->map(function ($portfolio) {
                return [
                    'id' => $portfolio->id,
                    'mahasiswa_id' => $portfolio->mahasiswa_id,
                    'nama' => $portfolio->nama,
                    'bidang' => $portfolio->bidang,
                    'deskripsi' => $portfolio->deskripsi,
                    'public_link' => $portfolio->public_link,
                    'is_public' => $portfolio->is_public,
                    'created_at' => $portfolio->created_at ? $portfolio->created_at->toISOString() : null,
                    'updated_at' => $portfolio->updated_at ? $portfolio->updated_at->toISOString() : null,
                ];
            });
    }
}

