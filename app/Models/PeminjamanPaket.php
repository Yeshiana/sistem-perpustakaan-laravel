<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeminjamanPaket extends Model
{
    protected $table = 'peminjaman_paket';

    protected $fillable = [
        'kode_peminjaman',
        'buku_id',
        'jumlah',
        'nama_peminjam',
        'kelas',
        'mata_pelajaran',
        'guru',
        'pengurus_id',
        'tgl_pinjam',
        'tgl_kembali',
        'tgl_dikembalikan',
        'jumlah_kembali',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tgl_pinjam'       => 'date',
        'tgl_kembali'      => 'date',
        'tgl_dikembalikan' => 'date',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function pengurus()
    {
        return $this->belongsTo(User::class, 'pengurus_id');
    }

    public static function generateKode(): string
    {
        $tanggalKode = now()->format('dmy');
        $likePattern = 'PKT' . $tanggalKode . '%';

        $jumlahHariIni = self::where('kode_peminjaman', 'like', $likePattern)->count();
        $urutan = str_pad($jumlahHariIni + 1, 3, '0', STR_PAD_LEFT);

        return 'PKT' . $tanggalKode . $urutan;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function (PeminjamanPaket $item) {
            if (empty($item->kode_peminjaman)) {
                $item->kode_peminjaman = self::generateKode();
            }
        });
    }
}