<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'pengurus_id',
        'kepala_id',
        'judul',
        'file_laporan',
        'keterangan',
        'status',
        'koreksi',
    ];

    public function pengurus()
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }

    public function kepala()
    {
        return $this->belongsTo(User::class, 'kepala_id');
    }
}