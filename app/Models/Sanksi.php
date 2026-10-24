<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sanksi extends Model
{
    protected $table = 'sanksi';

    // protected $fillable = [
    //     'peminjaman_id',
    //     'user_id',
    //     'jenis',
    //     'keterangan',
    //     'bukti_sanksi',
    //     'status',
    // ];


    protected $fillable = [
        'peminjaman_id',
        'user_id',
        'jenis',
        'keterangan',
        'bukti_sanksi',
        'status',
        'berlaku_sampai',
    ];

    protected $casts = [
        'berlaku_sampai' => 'date',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}