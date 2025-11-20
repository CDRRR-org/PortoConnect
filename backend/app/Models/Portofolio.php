<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Portofolio extends Model
{
    protected $fillable = [
        'mahasiswa_id',
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
}

