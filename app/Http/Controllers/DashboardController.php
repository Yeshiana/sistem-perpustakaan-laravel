<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kunjungan;
use App\Models\Laporan;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Notifikasi;
use App\Mail\PengingatPengembalianMail;
use Illuminate\Support\Facades\Mail;
// TAMBAHAN 1
use App\Models\Sanksi;
use App\Models\PeminjamanPaket;
use App\Models\Kategori;
class DashboardController extends Controller
{

public function siswa()
    {
        $buku = Buku::where('stok', '>', 0)
            ->latest()
            ->take(8)
            ->get();

        $pengingat    = $this->buatPengingatPeminjaman();
        $adaTerlambat = collect($pengingat)->contains('terlambat', true);

        return view('siswa.dashboard', compact('buku', 'pengingat', 'adaTerlambat'));
    }

    private function buatPengingatPeminjaman(): array
    {
        $peminjamanAktif = Peminjaman::with('buku')
            ->where('user_id', auth()->id())
            ->where('status', 'dipinjam')
            ->whereDoesntHave('pengembalian')
            ->whereNotNull('tgl_kembali')
            ->get();

        $hariIni   = today();
        $pengingat = [];

        foreach ($peminjamanAktif as $p) {
            $tglKembali = $p->tgl_kembali;
            $terlambat  = $tglKembali->lt($hariIni);
            $sisaHari   = $hariIni->diffInDays($tglKembali);

            if ($terlambat) {
                $teks = "Buku \"{$p->buku->judul}\" sudah terlambat {$sisaHari} hari, segera kembalikan";
            } elseif ($sisaHari === 0) {
                $teks = "Buku \"{$p->buku->judul}\" harus dikembalikan hari ini";
            } else {
                $teks = "Buku \"{$p->buku->judul}\" harus dikembalikan dalam {$sisaHari} hari lagi (jatuh tempo {$tglKembali->format('d-m-Y')})";
            }

            $pengingat[] = [
                'teks'      => $teks,
                'terlambat' => $terlambat,
            ];

            $this->simpanNotifikasiPengingat($p, $teks);
        }

        return $pengingat;
    }

    // Simpan notifikasi pengingat ke tabel notifikasi, maksimal 1x per peminjaman per hari
    // Simpan notifikasi pengingat ke tabel notifikasi, maksimal 1x per peminjaman per hari
    private function simpanNotifikasiPengingat(Peminjaman $peminjaman, string $teks): void
    {
        $sudahDikirimHariIni = Notifikasi::where('user_id', $peminjaman->user_id)
            ->where('kategori', 'peminjaman')
            ->where('pesan', 'like', '%'.$peminjaman->kode_peminjaman.'%')
            ->whereDate('created_at', today())
            ->exists();

        if ($sudahDikirimHariIni) {
            return;
        }

        Notifikasi::create([
            'user_id'  => $peminjaman->user_id,
            'pesan'    => "Pengingat [{$peminjaman->kode_peminjaman}]: ".$teks.'.',
            'kategori' => 'peminjaman',
            'is_read'  => false,
        ]);

        $this->kirimEmailPengingat($peminjaman, $teks);
    }

    // Kirim email pengingat pengembalian, dibungkus try-catch supaya
    // kalau pengiriman email gagal (misal koneksi internet mati saat demo),
    // sistem tetap jalan normal dan tidak menampilkan error ke pengguna
    private function kirimEmailPengingat(Peminjaman $peminjaman, string $teks): void
    {
        $email = $peminjaman->user->email ?? null;

        if (!$email) {
            return;
        }

        try {
            Mail::to($email)->send(new PengingatPengembalianMail($peminjaman, $teks));
        } catch (\Exception $e) {
            \Log::warning('Gagal mengirim email pengingat: '.$e->getMessage());
        }
    }

    // BATAS UBAH NOTIF LAYAR BERJALAN 
    // public function siswa()
    // {
    //     $buku = Buku::where('stok', '>', 0)
    //         ->latest()
    //         ->take(8)
    //         ->get();

    //     return view('siswa.dashboard', compact('buku'));
    // }



    public function kepala()
    {
        $totalBuku       = Buku::count();
        $totalAnggota    = User::whereIn('role', ['siswa', 'guru'])->where('is_active', 1)->count();
        $totalKunjungan  = Kunjungan::whereDate('tanggal', today())->count();
        $totalPinjam     = Peminjaman::whereIn('status', ['disetujui', 'menunggu_konfirmasi'])->count();
        $laporanMenunggu = Laporan::where('status', 'menunggu')->count();
        $laporanTerbaru  = Laporan::with('pengurus')->latest()->take(5)->get();

        return view('kepala.dashboard', compact(
            'totalBuku', 'totalAnggota', 'totalKunjungan',
            'totalPinjam', 'laporanMenunggu', 'laporanTerbaru'
        ));
    }

    public function pengurus()
{
    $totalBuku = Buku::count();

    $totalAnggota = User::whereIn('role', ['siswa', 'guru'])
                        ->count();

    $bukuDipinjam = Peminjaman::whereIn('status', [
        'dipinjam',
        'menunggu_konfirmasi',
        'disetujui'
    ])->count();

    $terlambat = Peminjaman::where('status', 'dipinjam')
                    ->whereDate('tgl_kembali', '<', today())
                    ->count();

    $totalKunjunganHariIni = Kunjungan::whereDate('tanggal', today())->count();

    $kunjunganHariIni = Kunjungan::whereDate('tanggal', today())
                    ->orderByDesc('jam_masuk')
                    ->take(10)
                    ->get();

    // Data tambahan untuk kartu "Perlu Tindakan"
    $menungguPersetujuan = Peminjaman::where('status', 'menunggu')->count();
    $sanksiBelumSelesai  = Sanksi::where('status', '!=', 'selesai')->count();
    $paketDipinjam       = PeminjamanPaket::where('status', 'dipinjam')->count();
    $totalKategori       = Kategori::count();

    return view('pengurus.dashboard', compact(
        'totalBuku',
        'totalAnggota',
        'bukuDipinjam',
        'terlambat',
        'totalKunjunganHariIni',
        'kunjunganHariIni',
        'menungguPersetujuan',
        'sanksiBelumSelesai',
        'paketDipinjam',
        'totalKategori'
    ));
}

//     public function pengurus()
// {
//     $totalBuku = Buku::count();

//     $totalAnggota = User::whereIn('role', ['siswa', 'guru'])
//                         ->count();

//     $bukuDipinjam = Peminjaman::whereIn('status', [
//         'dipinjam',
//         'menunggu_konfirmasi',
//         'disetujui'
//     ])->count();

//     $terlambat = Peminjaman::where('status', 'dipinjam')
//                     ->whereDate('tgl_kembali', '<', today())
//                     ->count();

//     $totalKunjunganHariIni = Kunjungan::whereDate('tanggal', today())->count();

//     $kunjunganHariIni = Kunjungan::whereDate('tanggal', today())
//                     ->orderByDesc('jam_masuk')
//                     ->take(10)
//                     ->get();

//     return view('pengurus.dashboard', compact(
//         'totalBuku',
//         'totalAnggota',
//         'bukuDipinjam',
//         'terlambat',
//         'totalKunjunganHariIni',
//         'kunjunganHariIni'
//     ));
// }

}