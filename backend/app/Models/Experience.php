<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'judul',
        'perusahaan',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_selesai',
        'masih_berlangsung',
        'tipe',
        'urutan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'masih_berlangsung' => 'boolean',
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}

