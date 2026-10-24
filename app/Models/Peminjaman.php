<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    // protected $fillable = [
    //     'kode_peminjaman',
    //     'user_id',
    //     'buku_id',
    //     'tgl_booking',
    //     'tgl_ambil',
    //     'bukti_pengambilan',
    //     'tgl_pinjam',
    //     'tgl_kembali',
    //     'status',
    // ];

    // protected $casts = [
    //     'tgl_booking' => 'date',
    //     'tgl_ambil'   => 'date',
    //     'tgl_pinjam'  => 'date',
    //     'tgl_kembali' => 'date',
    // ];
// BATAS PERUBAHAN TGL FIX

protected $fillable = [
        'kode_peminjaman',
        'user_id',
        'buku_id',
        'tgl_booking',
        'tgl_ambil',
        'tgl_ambil_fix',
        'bukti_pengambilan',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
    ];

    protected $casts = [
        'tgl_booking'   => 'date',
        'tgl_ambil'     => 'date',
        'tgl_ambil_fix' => 'date',
        'tgl_pinjam'    => 'date',
        'tgl_kembali'   => 'date',
    ];




    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class);
    }

    public function pengembalian()
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function sanksi()
        {
            return $this->hasOne(Sanksi::class);
        }

        public function daftarSanksi()
        {
            return $this->hasMany(Sanksi::class);
        }
    /**
     * Generate kode peminjaman otomatis.
     * Format: PJM + [role: SIS/GRU] + [tanggal ddmmyy] + [nomor urut 3 digit]
     * Contoh: PJMSIS050726001
     */
    public static function generateKodePeminjaman(int $userId): string
    {
        $user = User::find($userId);

        $kodeRole = match($user?->role) {
            'siswa'    => 'SIS',
            'guru'     => 'GRU',
            'pengurus' => 'PNG',
            default    => 'ANG',
        };

        $tanggalKode = now()->format('dmy');
        $likePattern = 'PJM' . $kodeRole . $tanggalKode . '%';

        $jumlahHariIni = self::where('kode_peminjaman', 'like', $likePattern)->count();
        $urutan        = $jumlahHariIni + 1;
        $kode          = 'PJM' . $kodeRole . $tanggalKode . str_pad($urutan, 3, '0', STR_PAD_LEFT);

        // Kalau kode ini ternyata sudah dipakai (misal ada data lama yang sempat
        // dihapus manual sehingga hitungan COUNT meleset), coba nomor urut
        // berikutnya sampai ketemu yang benar-benar belum dipakai.
        while (self::where('kode_peminjaman', $kode)->exists()) {
            $urutan++;
            $kode = 'PJM' . $kodeRole . $tanggalKode . str_pad($urutan, 3, '0', STR_PAD_LEFT);
        }

        return $kode;
    }

    // Auto-generate kode_peminjaman saat transaksi baru dibuat
     
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Peminjaman $peminjaman) {
            if (empty($peminjaman->kode_peminjaman) && $peminjaman->user_id) {
                $peminjaman->kode_peminjaman = self::generateKodePeminjaman($peminjaman->user_id);
            }
        });
    }

    /**
     * Hitung tgl kembali (skip Sabtu & Minggu)
     */
    public static function hitungTglKembali($tglAmbil, $hariKerja = 3)
    {
        $tanggal  = \Carbon\Carbon::parse($tglAmbil);
        $hitungan = 0;

        while ($hitungan < $hariKerja) {
            $tanggal->addDay();
            if (!$tanggal->isWeekend()) {
                $hitungan++;
            }
        }

        return $tanggal;
    }
}