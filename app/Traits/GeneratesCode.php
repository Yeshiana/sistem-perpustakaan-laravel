<?php

namespace App\Traits;

trait GeneratesCode
{
    /**
     * Generate kode otomatis dengan format:
     * [PREFIX][ROLE/JENIS][TANGGAL ddmmyy][NOMOR URUT 3 digit]
     *
     * @param  string  $kolomKode      Nama kolom kode pada tabel, contoh: 'kode_peminjaman'
     * @param  string  $prefix         Awalan kode entitas, contoh: 'PJM'
     * @param  string  $kodeTengah     Kode role/jenis, contoh: 'SIS', 'GRU', 'KTL'
     * @param  \Carbon\Carbon|string|null  $tanggal   Tanggal acuan, default hari ini
     * @return string
     */
    public static function generateKode(string $kolomKode, string $prefix, string $kodeTengah, $tanggal = null): string
    {
        $tanggal = $tanggal ? \Carbon\Carbon::parse($tanggal) : now();
        $tanggalKode = $tanggal->format('dmy'); // ddmmyy

        $likePattern = $prefix . $kodeTengah . $tanggalKode . '%';

        $jumlahHariIni = static::query()
            ->where($kolomKode, 'like', $likePattern)
            ->count();

        $urutan = str_pad($jumlahHariIni + 1, 3, '0', STR_PAD_LEFT);

        return $prefix . $kodeTengah . $tanggalKode . $urutan;
    }
}