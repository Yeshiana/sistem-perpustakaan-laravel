<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    protected $table = 'kunjungan';

    protected $fillable = [
        'user_id',
        'nama_pengunjung',
        'kelas_pengunjung',
        'jenis_pengunjung',
        'tujuan',
        'tanggal',
        'jam_masuk',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}