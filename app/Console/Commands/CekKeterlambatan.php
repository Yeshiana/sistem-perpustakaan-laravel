<?php

namespace App\Console\Commands;

use App\Models\Peminjaman;
use App\Models\Sanksi;
use App\Models\Notifikasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CekKeterlambatan extends Command
{
    protected $signature = 'sanksi:cek-keterlambatan';
    protected $description = 'Cek peminjaman yang terlambat dan buat sanksi otomatis';

    public function handle()
    {
        $today = Carbon::today();

        // Ambil semua peminjaman yang statusnya dipinjam dan sudah lewat tgl kembali
        $terlambat = Peminjaman::with(['buku', 'user'])
            ->where('status', 'dipinjam')
            ->whereDate('tgl_kembali', '<', $today)
            ->get();

        foreach ($terlambat as $peminjaman) {
            // Cek apakah sanksi keterlambatan sudah ada
            $sudahAdaSanksi = Sanksi::where('peminjaman_id', $peminjaman->id)
                ->where('jenis', 'keterlambatan')
                ->exists();

            if (!$sudahAdaSanksi) {
                // Buat sanksi keterlambatan
                Sanksi::create([
                    'peminjaman_id' => $peminjaman->id,
                    'user_id'       => $peminjaman->user_id,
                    'jenis'         => 'keterlambatan',
                    'keterangan'    => 'Buku "'.$peminjaman->buku->judul.'" terlambat dikembalikan. Sanksi: membersihkan/merapihkan area perpustakaan.',
                    'status'        => 'menunggu',
                ]);

                // Notifikasi ke siswa
                Notifikasi::create([
                    'user_id' => $peminjaman->user_id,
                    'pesan'   => 'Kamu terlambat mengembalikan buku "'.$peminjaman->buku->judul.'". Kamu mendapat sanksi membersihkan/merapihkan area perpustakaan. Segera upload bukti kegiatan!',
                    'kategori' => 'sanksi',
                    'is_read' => false,
                ]);

                // Notifikasi ke pengurus
                $pengurus = User::where('role', 'pengurus')->first();
                if ($pengurus) {
                    Notifikasi::create([
                        'user_id' => $pengurus->id,
                        'pesan'   => $peminjaman->user->name.' terlambat mengembalikan buku "'.$peminjaman->buku->judul.'". Sanksi keterlambatan telah dibuat otomatis.',
                        'kategori' => 'sanksi',
                        'is_read' => false,
                    ]);
                }

                $this->info('Sanksi dibuat untuk: '.$peminjaman->user->name.' - '.$peminjaman->buku->judul);
            }
        }

        $this->info('Pengecekan keterlambatan selesai!');
    }
}