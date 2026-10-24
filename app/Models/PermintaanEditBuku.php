<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermintaanEditBuku extends Model
{
    protected $table = 'permintaan_edit_buku';

    protected $fillable = [
        'buku_id',
        'user_id',
        'field_diminta',
        'alasan',
        'status',
        'disetujui_oleh',
        'catatan_kepala',
        'digunakan_at',
    ];

    protected $casts = [
        'field_diminta' => 'array',
        'digunakan_at'  => 'datetime',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function pengaju()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function penyetuju()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public static function labelField(string $field): string
    {
        return match ($field) {
            'judul'        => 'Judul Buku',
            'pengarang'    => 'Pengarang',
            'kategori_id'  => 'Kategori',
            'isbn'         => 'ISBN',
            'penerbit'     => 'Penerbit',
            'tahun_terbit' => 'Tahun Terbit',
            'stok'         => 'Stok',
            'cover'        => 'Cover',
            default        => ucfirst($field),
        };
    }
}