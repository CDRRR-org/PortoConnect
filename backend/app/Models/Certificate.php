<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certificate extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'portofolio_id',
        'nama',
        'penerbit',
        'tanggal_terbit',
        'tanggal_kadaluarsa',
        'file_path',
        'link',
        'urutan',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'tanggal_kadaluarsa' => 'date',
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

