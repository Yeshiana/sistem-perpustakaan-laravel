<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use App\Models\Notifikasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BatalkanPeminjamanKadaluarsa extends Command
{
    protected $signature = 'peminjaman:batalkan-kadaluarsa';
    protected $description = 'Batalkan otomatis peminjaman yang tidak diambil';

    public function handle()
    {
        $peminjaman = Peminjaman::whereIn('status', ['menunggu', 'disetujui'])
            ->whereDate('tgl_ambil', '<', Carbon::today())
            ->get();

        foreach ($peminjaman as $item) {
            $item->update([
                'status' => 'dibatalkan',
            ]);

            // Kembalikan stok
            $item->buku->increment('stok');

            // Notifikasi ke siswa
Notifikasi::create([
    'user_id'  => $item->user_id,
    'pesan'    => 'Peminjaman buku "'.$item->buku->judul.'" dibatalkan otomatis karena tidak diambil pada tanggal yang ditentukan.',
    'kategori' => 'peminjaman',
    'is_read'  => false,
]);

// Notifikasi ke pengurus
$pengurus = User::where('role', 'pengurus')->first();
if ($pengurus) {
    Notifikasi::create([
        'user_id'  => $pengurus->id,
        'pesan'    => 'Peminjaman buku "'.$item->buku->judul.'" oleh '.$item->user->name.' dibatalkan otomatis karena tidak diambil.',
        'kategori' => 'peminjaman',
        'is_read'  => false,
    ]);
}
        }

        $this->info('Selesai! '.$peminjaman->count().' peminjaman dibatalkan.');
    }
}