<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'portofolio_id',
        'judul',
        'deskripsi',
        'link',
        'gambar',
        'teknologi',
        'tanggal_mulai',
        'tanggal_selesai',
        'urutan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function portofolio(): BelongsTo
    {
        return $this->belongsTo(Portofolio::class);
    }
}

