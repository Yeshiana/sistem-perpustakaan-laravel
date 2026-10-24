<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengembalian extends Model
{
    protected $table = 'pengembalian';

    protected $fillable = [
        'kode_pengembalian',
        'peminjaman_id',
        'tgl_lapor',
        'bukti_pengembalian_mandiri',
        'tgl_kembali_aktual',
        'dikonfirmasi_oleh',
        'tgl_konfirmasi',
        'kondisi_buku',
        'status_keterlambatan',
        'sanksi_kegiatan',
        'bukti_sanksi',
        'status_penggantian',
        'bukti_penggantian',
    ];

    protected $casts = [
        'tgl_lapor'          => 'date',
        'tgl_kembali_aktual' => 'date',
        'tgl_konfirmasi'     => 'date',
    ];

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class);
    }
}