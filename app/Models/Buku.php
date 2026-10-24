<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    protected $table = 'buku';

    
    protected $fillable = [
    'judul',
    'pengarang',
    'kategori_id',
    'kode_buku',
    'isbn',
    'penerbit',
    'tahun_terbit',
    'rak',
    'sinopsis',
    'stok',
    'stok_tersedia',   // <-- ini baris baru yang ditambahkan
    'cover',
];
    
    /**
     * Relasi Buku -> Kategori
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Ambil 3 huruf pertama dari nama kategori.
     * Contoh: "Fiksi" → FIK, "Buku Pelajaran" → BUK, "Sains" → SAI
     */
    public static function kodeKategori(string $namaKategori): string
{
    // Ambil huruf pertama setiap kata, max 3 karakter
    $kata   = preg_split('/\s+/', trim($namaKategori));
    $inisial = '';

    foreach ($kata as $k) {
        $bersih = preg_replace('/[^A-Za-z]/', '', $k);
        if ($bersih) $inisial .= strtoupper($bersih[0]);
        if (strlen($inisial) >= 3) break;
    }

    // Kalau hanya 1 kata, ambil 3 huruf pertama kata itu
    if (strlen($inisial) < 3) {
        $bersih  = preg_replace('/[^A-Za-z]/', '', $namaKategori);
        $inisial = strtoupper(substr($bersih, 0, 3));
    }

    return str_pad($inisial, 3, 'X');
}

    /**
     * Generate kode buku unik.
     * Format: BUK + [3 huruf kategori] + [3 digit nomor urut dalam kategori]
     * Contoh: BUKFIK001, BUKFIK002, BUKPEL001
     */
    public static function generateKodeBuku(int $kategoriId): string
{
    $kategori = Kategori::find($kategoriId);
    $kodeKat  = $kategori ? self::kodeKategori($kategori->nama) : 'UMM';

    // Hitung jumlah buku dalam kategori yang sama
    $jumlah = self::where('kategori_id', $kategoriId)->count();
    $urutan = str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);

    $kode = 'BUK' . $kodeKat . $urutan;

    // Kalau kode sudah ada, tambah urutan sampai unik
    while (self::where('kode_buku', $kode)->exists()) {
        $jumlah++;
        $urutan = str_pad($jumlah + 1, 3, '0', STR_PAD_LEFT);
        $kode   = 'BUK' . $kodeKat . $urutan;
    }

    return $kode;
}
    /**
     * Auto-generate kode_buku saat buku baru dibuat
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Buku $buku) {
            if (empty($buku->kode_buku) && $buku->kategori_id) {
                $buku->kode_buku = self::generateKodeBuku($buku->kategori_id);
            }
        });
    }
}