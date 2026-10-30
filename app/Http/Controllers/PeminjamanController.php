<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\User;
use App\Models\Notifikasi;
use App\Models\Sanksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanController extends Controller
{
    // Pengurus - Lihat semua request peminjaman
    // public function index(Request $request)
    // {
    //     $query = Peminjaman::with(['user', 'buku', 'daftarSanksi']);

    //     if ($request->status) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->search) {
    //         $query->whereHas('user', function($q) use ($request) {
    //             $q->where('name', 'like', '%'.$request->search.'%');
    //         })->orWhereHas('buku', function($q) use ($request) {
    //             $q->where('judul', 'like', '%'.$request->search.'%');
    //         });
    //     }

    //     $peminjaman = $query->latest()->paginate(10);
    //     return view('pengurus.peminjaman.index', compact('peminjaman'));
    // }


    public function index(Request $request)
{
    // TAMBAHAN
    $this->batalkanPeminjamanKedaluwarsa();
    $this->tandaiKeterlambatanOtomatis();

    $query = Peminjaman::with(['user', 'buku', 'daftarSanksi']);

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->search) {
        $query->whereHas('user', function($q) use ($request) {
            $q->where('name', 'like', '%'.$request->search.'%');
        })->orWhereHas('buku', function($q) use ($request) {
            $q->where('judul', 'like', '%'.$request->search.'%');
        });
    }

    $peminjaman = $query->latest()->paginate(10);

    // Kelompokkan baris yang berurutan, peminjam sama, dan tanggal booking sama,
    // supaya di tampilan bisa dikasih rowspan (tanpa ubah struktur database).
    $rowspanInfo = [];
    $items = $peminjaman->items();

    for ($i = 0; $i < count($items); $i++) {
        $current = $items[$i];

        if (isset($rowspanInfo[$current->id])) {
            continue;
        }

        $groupIds = [$current->id];

        for ($j = $i + 1; $j < count($items); $j++) {
            $next = $items[$j];
            $samaPeminjam = $next->user_id == $current->user_id;
            $samaTanggal  = $next->tgl_booking && $current->tgl_booking
                             && $next->tgl_booking->isSameDay($current->tgl_booking);

            if ($samaPeminjam && $samaTanggal) {
                $groupIds[] = $next->id;
            } else {
                break;
            }
        }

        $kodeTransaksi = 'TRX-'.strtoupper(substr(
            md5($current->user_id.$current->tgl_booking?->format('Ymd')), 0, 6
        ));

        foreach ($groupIds as $index => $id) {
    $rowspanInfo[$id] = [
        'is_first'       => $index === 0,
        'rowspan'        => count($groupIds),
        'kode_transaksi' => $kodeTransaksi,
        'group_key'      => 'grp'.$current->id,
    ];
}
    }

    return view('pengurus.peminjaman.index', compact('peminjaman', 'rowspanInfo'));
}

    // Pengurus - ACC peminjaman
   public function approve(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tgl_ambil_fix' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->isWeekend()) {
                        $fail('Tanggal ambil fix tidak boleh hari Sabtu atau Minggu (perpustakaan tutup).');
                    }
                },
            ],
        ]);

        $tglAmbilFix = Carbon::parse($request->tgl_ambil_fix);
        $tglKembali  = Peminjaman::hitungTglKembali($tglAmbilFix);

        $peminjaman->update([
            'status'        => 'disetujui',
            'tgl_ambil_fix' => $tglAmbilFix,
            'tgl_kembali'   => $tglKembali,
        ]);

        Notifikasi::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Peminjaman buku "'.$peminjaman->buku->judul.'" disetujui! Ambil buku pada tanggal '.$tglAmbilFix->format('d M Y').' sebelum jam 15:00 (jam operasional perpustakaan). Jika tidak diambil sampai tanggal tersebut, peminjaman otomatis dibatalkan dan kamu dikenakan larangan meminjam selama 2 minggu.',
            'kategori' => 'peminjaman',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil disetujui!');
    }

    // Pengurus - Tolak peminjaman
    public function tolak(Peminjaman $peminjaman)
    {
        $peminjaman->update(['status' => 'ditolak']);
        $peminjaman->buku->increment('stok_tersedia');

        Notifikasi::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Maaf, peminjaman buku "'.$peminjaman->buku->judul.'" ditolak.',
            'kategori' => 'peminjaman',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Peminjaman berhasil ditolak!');
    }

    // Pengurus - Tampilkan form pinjam offline (anggota datang langsung ke perpus)
    // Pengurus - Tampilkan form pinjam offline (anggota datang langsung ke perpus)
public function createOffline()
{
    return view('pengurus.peminjaman.offline');
}

// Pengurus - Cari anggota (dipakai AJAX di form pinjam offline)
public function cariAnggota(Request $request)
{
    $search = $request->q;

    $anggota = User::whereIn('role', ['siswa', 'guru'])
        ->where('is_active', true)
        ->when($search, function ($q) use ($search) {
            $q->where('name', 'like', '%'.$search.'%');
        })
        ->orderBy('name')
        ->limit(20)
        ->get();

    $results = $anggota->map(function ($item) {
        return [
            'id'   => $item->id,
            'text' => $item->name.' - '.ucfirst($item->role)
                      .($item->role == 'siswa' && $item->kelas ? ' ('.$item->kelas.')' : ''),
        ];
    });

    return response()->json(['results' => $results]);
}

// Pengurus - Cari buku (dipakai AJAX di form pinjam offline)
public function cariBuku(Request $request)
{
    $search = $request->q;

    $buku = Buku::where('stok_tersedia', '>', 0)
        ->when($search, function ($q) use ($search) {
            $q->where('judul', 'like', '%'.$search.'%');
        })
        ->orderBy('judul')
        ->limit(20)
        ->get();

    $results = $buku->map(function ($item) {
        return [
            'id'   => $item->id,
            'text' => $item->judul.' (Stok tersedia: '.$item->stok_tersedia.')',
        ];
    });

    return response()->json(['results' => $results]);
}

// Pengurus - Simpan peminjaman offline (anggota datang langsung, admin tetap upload bukti foto)
public function storeOffline(Request $request)
{
    $request->validate([
        'user_id'           => 'required|exists:users,id',
        'buku_id'           => 'required|exists:buku,id',
        'bukti_pengambilan' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $errorBatas = $this->cekBatasPeminjaman($request->user_id);
    if ($errorBatas) {
        return redirect()->back()->with('error', $errorBatas);
    }

    $buku = Buku::findOrFail($request->buku_id);

    if ($buku->stok_tersedia <= 0) {
        return redirect()->back()->with('error', 'Stok buku habis!');
    }

    $sudahPinjam = Peminjaman::where('user_id', $request->user_id)
        ->where('buku_id', $request->buku_id)
        ->whereIn('status', ['menunggu', 'disetujui', 'menunggu_konfirmasi', 'dipinjam'])
        ->exists();

    if ($sudahPinjam) {
        return redirect()->back()->with('error', 'Anggota ini sudah meminjam atau mengajukan buku ini!');
    }

    $path = $request->file('bukti_pengambilan')->store('bukti_pengambilan', 'public');

    $tglPinjam  = Carbon::today();
    $tglKembali = Peminjaman::hitungTglKembali($tglPinjam);

    // Admin menyaksikan langsung serah-terima, jadi stok fisik & stok tersedia
    // dikurangi sekaligus di titik yang sama (beda dari alur online yang dipisah 2 tahap)
    $buku->decrement('stok');
    $buku->decrement('stok_tersedia');

    Peminjaman::create([
        'user_id'           => $request->user_id,
        'buku_id'           => $request->buku_id,
        'tgl_booking'       => $tglPinjam,
        'tgl_ambil'         => $tglPinjam,
        'tgl_pinjam'        => $tglPinjam,
        'tgl_kembali'       => $tglKembali,
        'bukti_pengambilan' => $path,
        'status'            => 'dipinjam',
    ]);

    Notifikasi::create([
        'user_id'  => $request->user_id,
        'pesan'    => 'Kamu meminjam buku "'.$buku->judul.'" langsung di perpustakaan pada '.$tglPinjam->format('d M Y').'. Harap kembalikan sebelum '.$tglKembali->format('d M Y').'.',
        'kategori' => 'peminjaman',
        'is_read'  => false,
    ]);

    return redirect()->route('pengurus.peminjaman.index')
                     ->with('success', 'Peminjaman offline berhasil dicatat!');
}

    // Pengurus - Konfirmasi foto pengambilan
//     public function konfirmasiAmbil(Request $request, Peminjaman $peminjaman)
// {
//     $request->validate([
//         'bukti_pengambilan' => 'required|image|mimes:jpg,jpeg,png|max:2048',
//     ]);

//     $path = $request->file('bukti_pengambilan')->store('bukti_pengambilan', 'public');

//     $tglKembali = Peminjaman::hitungTglKembali($peminjaman->tgl_ambil);

//     $peminjaman->update([
//         'status'            => 'dipinjam',
//         'tgl_pinjam'        => $peminjaman->tgl_ambil,
//         'tgl_kembali'       => $tglKembali,
//         'bukti_pengambilan' => $path,
//     ]);

//     // BARIS BARU: stok fisik baru berkurang sekarang, saat buku benar-benar diambil
//     $peminjaman->buku->decrement('stok');

//     Notifikasi::create([
//         'user_id' => $peminjaman->user_id,
//         'pesan'   => 'Pengambilan buku "'.$peminjaman->buku->judul.'" dikonfirmasi. Harap kembalikan sebelum '.$tglKembali->format('d M Y').'.',
//         'kategori' => 'peminjaman',
//         'is_read' => false,
//     ]);

//     return redirect()->back()->with('success', 'Pengambilan buku dikonfirmasi!');
// }


public function konfirmasiAmbil(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'bukti_pengambilan' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('bukti_pengambilan')->store('bukti_pengambilan', 'public');

        $peminjaman->update([
            'status'            => 'dipinjam',
            'tgl_pinjam'        => Carbon::today(),
            'bukti_pengambilan' => $path,
            // tgl_kembali TIDAK dihitung ulang - sudah ditentukan sejak approve (tgl_ambil_fix)
        ]);

        $peminjaman->buku->decrement('stok');

        Notifikasi::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Pengambilan buku "'.$peminjaman->buku->judul.'" dikonfirmasi. Harap kembalikan sebelum '.$peminjaman->tgl_kembali->format('d M Y').'.',
            'kategori' => 'peminjaman',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Pengambilan buku dikonfirmasi!');
    }

// Konfirmasi pengembalian buku
public function kembalikan(Request $request, Peminjaman $peminjaman)
{
    $kondisi = $request->input('kondisi', 'baik');
    $tanggalKembaliAktual = Carbon::today();
    $adaPelanggaran = false;

    $peminjaman->update(['status' => 'selesai']);

    // Stok (fisik & tersedia) hanya bertambah kalau buku kembali dalam kondisi baik.
    // Kalau rusak, stok belum ditambah sampai ada penggantinya.
    if ($kondisi == 'baik') {
        $peminjaman->buku->increment('stok');
        $peminjaman->buku->increment('stok_tersedia');
    }

    // Cek keterlambatan
    if ($tanggalKembaliAktual->gt($peminjaman->tgl_kembali)) {
        $adaPelanggaran = true;

        $sudahAdaSanksiTelat = Sanksi::where('peminjaman_id', $peminjaman->id)
            ->where('jenis', 'keterlambatan')
            ->exists();

        // Hindari sanksi dobel kalau sudah ditandai otomatis sebelumnya oleh tandaiKeterlambatanOtomatis()
        if (!$sudahAdaSanksiTelat) {
            $telatHari = $peminjaman->tgl_kembali->diffInDays($tanggalKembaliAktual);

            Sanksi::create([
                'peminjaman_id' => $peminjaman->id,
                'user_id'       => $peminjaman->user_id,
                'jenis'         => 'keterlambatan',
                'keterangan'    => 'Terlambat mengembalikan buku "'.$peminjaman->buku->judul.'" selama '.$telatHari.' hari (batas waktu: '.$peminjaman->tgl_kembali->format('d M Y').'). Sanksi: membersihkan perpustakaan, upload foto bukti kegiatan sebagai bukti sanksi.',
                'status'        => 'menunggu',
            ]);

            Notifikasi::create([
                'user_id' => $peminjaman->user_id,
                'pesan'   => 'Kamu terlambat mengembalikan buku "'.$peminjaman->buku->judul.'" selama '.$telatHari.' hari. Sebagai sanksi, kamu diminta membersihkan perpustakaan. Setelah selesai, upload foto bukti kegiatan di menu Sanksi.',
                'kategori' => 'sanksi',
                'is_read' => false,
            ]);
        }
    }

    // Jika buku rusak, buat sanksi otomatis (terpisah — siswa bisa kena 2 sanksi sekaligus: telat + rusak)
    if ($kondisi == 'rusak') {
        $adaPelanggaran = true;

        Sanksi::create([
            'peminjaman_id' => $peminjaman->id,
            'user_id'       => $peminjaman->user_id,
            'jenis'         => 'buku_rusak',
            'keterangan'    => 'Buku "'.$peminjaman->buku->judul.'" dikembalikan dalam kondisi rusak. Harap ganti dengan buku serupa dan upload buktinya.',
            'status'        => 'menunggu',
        ]);

        Notifikasi::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Buku "'.$peminjaman->buku->judul.'" yang kamu kembalikan dalam kondisi rusak. Kamu mendapat sanksi untuk mengganti dengan buku serupa. Harap upload bukti penggantinya di menu Sanksi.',
            'kategori' => 'sanksi',
            'is_read' => false,
        ]);
    }

    // Notifikasi "terima kasih" HANYA kalau tidak ada pelanggaran sama sekali
    if (!$adaPelanggaran) {
        Notifikasi::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Pengembalian buku "'.$peminjaman->buku->judul.'" telah dikonfirmasi. Terima kasih!',
            'kategori' => 'peminjaman',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Pengembalian buku dikonfirmasi!');
    }

    return redirect()->back()->with('success', 'Pengembalian dikonfirmasi! Sanksi telah dicatat.');
}
// Siswa/Guru - Lihat katalog
    // Semua buku ditampilkan (termasuk stok habis, ditandai di tampilan).
    // Buku yang masih tersedia diurutkan lebih dulu.
    public function katalog(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('judul', 'like', '%'.$request->search.'%')
                  ->orWhere('pengarang', 'like', '%'.$request->search.'%');
            });
        }

        $buku = $query
            ->orderByRaw('stok_tersedia > 0 DESC')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('siswa.katalog', compact('buku'));
    }

    // Siswa/Guru - Request pinjam buku
    public function request(Request $request)
{
    $request->validate([
        'buku_id'   => 'required|exists:buku,id',
        'tgl_ambil' => 'required|date',
    ]);

    $errorBatas = $this->cekBatasPeminjaman(auth()->id());
    if ($errorBatas) {
        return redirect()->back()->with('error', $errorBatas);
    }

    // Tentukan tanggal dasar booking berdasarkan jam operasional (tutup jam 15:00)

    // Tentukan tanggal dasar booking berdasarkan jam operasional (tutup jam 15:00)
    // Booking jam 14:00 ke atas otomatis dihitung mulai besok
    $sekarang = Carbon::now();
    $tanggalDasar = $sekarang->hour >= 14 ? Carbon::tomorrow() : Carbon::today();

    $tglAmbil = Carbon::parse($request->tgl_ambil);

    if ($tglAmbil->lt($tanggalDasar)) {
        return redirect()->back()->with('error', 'Booking untuk hari ini sudah ditutup (setelah jam 13:59). Tanggal ambil paling cepat adalah '.$tanggalDasar->format('d M Y').'.');
    }

    $maxTgl = $this->tambahHariKerja($tanggalDasar, 2);

    if ($tglAmbil->gt($maxTgl)) {
        return redirect()->back()->with('error', 'Tanggal ambil maksimal '.$maxTgl->format('d M Y').'!');
    }

    // Cek apakah sudah ada peminjaman aktif
    $sudahPinjam = Peminjaman::where('user_id', auth()->id())
        ->where('buku_id', $request->buku_id)
        ->whereIn('status', ['menunggu', 'disetujui', 'menunggu_konfirmasi', 'dipinjam'])
        ->exists();

    if ($sudahPinjam) {
        return redirect()->back()->with('error', 'Kamu sudah meminjam atau mengajukan buku ini!');
    }

    $buku = Buku::find($request->buku_id);
if ($buku->stok_tersedia <= 0) {
    return redirect()->back()->with('error', 'Stok buku habis!');
}

$buku->decrement('stok_tersedia');
    Peminjaman::create([
        'user_id'     => auth()->id(),
        'buku_id'     => $request->buku_id,
        'tgl_booking' => Carbon::today(),
        'tgl_ambil'   => $tglAmbil,
        'tgl_pinjam'  => null,
        'status'      => 'menunggu',
    ]);

    $semuaPengurus = User::where('role', 'pengurus')->get();
    foreach ($semuaPengurus as $pengurus) {
        Notifikasi::create([
            'user_id'  => $pengurus->id,
            'pesan'    => auth()->user()->name.' mengajukan peminjaman buku "'.$buku->judul.'" untuk diambil pada '.$tglAmbil->format('d M Y').'.',
            'kategori' => 'peminjaman',
            'is_read'  => false,
        ]);
    }

    return redirect()->back()->with('success', 'Request peminjaman berhasil! Tunggu persetujuan pengurus.');
}
    // Siswa/Guru - Upload bukti pengambilan
    public function uploadBukti(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'bukti_pengambilan' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('bukti_pengambilan')->store('bukti_pengambilan', 'public');

        $peminjaman->update([
            'bukti_pengambilan' => $path,
            'status'            => 'menunggu_konfirmasi',
        ]);

        // Notifikasi ke pengurus 
        // Notifikasi ke semua pengurus
// Notifikasi ke semua pengurus
$semuaPengurus = User::where('role', 'pengurus')->get();
foreach ($semuaPengurus as $pengurus) {
    Notifikasi::create([
        'user_id'  => $pengurus->id,
        'pesan'    => auth()->user()->name.' telah mengupload bukti pengambilan buku "'.$peminjaman->buku->judul.'". Harap dikonfirmasi.',
        'kategori' => 'peminjaman',
        'is_read'  => false,
    ]);
}
        return redirect()->back()->with('success', 'Bukti pengambilan berhasil diupload! Tunggu konfirmasi pengurus.');
    }

    // Siswa/Guru - Riwayat peminjaman
    // public function riwayat()
    // {
    //     $peminjaman = Peminjaman::with(['buku'])
    //         ->where('user_id', auth()->id())
    //         ->latest()
    //         ->paginate(10);
    //     return view('siswa.riwayat-peminjaman', compact('peminjaman'));
    // }

        public function riwayat(Request $request)
    {
        $this->batalkanPeminjamanKedaluwarsa();
        $this->tandaiKeterlambatanOtomatis();

        $query = Peminjaman::with(['buku'])
            ->where('user_id', auth()->id());

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('kode_peminjaman', 'like', '%'.$request->search.'%')
                  ->orWhereHas('buku', function($q2) use ($request) {
                      $q2->where('judul', 'like', '%'.$request->search.'%');
                  });
            });
        }

        $peminjaman = $query->latest()->paginate(10)->withQueryString();
        return view('siswa.riwayat-peminjaman', compact('peminjaman'));
    }


    // Helper - Cek apakah anggota masih boleh meminjam (batas 3 aktif & tidak ada sanksi menggantung)
// private function cekBatasPeminjaman($userId): ?string
// {
//     // Cek sanksi yang belum selesai diverifikasi (keterlambatan maupun buku rusak)
//     $sanksiAktif = Sanksi::where('user_id', $userId)
//         ->where('status', '!=', 'selesai')
//         ->exists();

//     if ($sanksiAktif) {
//         return 'Kamu memiliki sanksi yang belum diselesaikan. Selesaikan sanksinya dan tunggu diverifikasi admin sebelum bisa meminjam buku lagi.';
//     }

//     // Cek jumlah peminjaman yang masih aktif (belum selesai)
//     $jumlahAktif = Peminjaman::where('user_id', $userId)
//         ->whereIn('status', ['menunggu', 'disetujui', 'menunggu_konfirmasi', 'dipinjam'])
//         ->count();

//     if ($jumlahAktif >= 3) {
//         return 'Kamu sudah mencapai batas maksimal 3 peminjaman aktif. Kembalikan salah satu buku terlebih dahulu sebelum meminjam lagi.';
//     }

//     return null;
// }

// Helper - Batalkan otomatis peminjaman yang disetujui tapi tidak diambil sampai tgl_ambil_fix,
    // lalu kenakan larangan meminjam 2 minggu ke peminjamnya
    private function batalkanPeminjamanKedaluwarsa(): void
    {
        $expired = Peminjaman::with('buku')
            ->where('status', 'disetujui')
            ->whereNotNull('tgl_ambil_fix')
            ->whereDate('tgl_ambil_fix', '<', Carbon::today())
            ->get();

        foreach ($expired as $peminjaman) {
            $peminjaman->update(['status' => 'dibatalkan']);
            $peminjaman->buku->increment('stok_tersedia');

            $berlakuSampai = Carbon::today()->addDays(14);

            Sanksi::create([
                'peminjaman_id'  => $peminjaman->id,
                'user_id'        => $peminjaman->user_id,
                'jenis'          => 'tidak_diambil',
                'keterangan'     => 'Tidak mengambil buku "'.$peminjaman->buku->judul.'" sampai batas waktu ('.$peminjaman->tgl_ambil_fix->format('d M Y').'). Peminjaman dibatalkan otomatis dan dikenakan larangan meminjam selama 2 minggu.',
                'status'         => 'selesai',
                'berlaku_sampai' => $berlakuSampai,
            ]);

            Notifikasi::create([
                'user_id'  => $peminjaman->user_id,
                'pesan'    => 'Peminjaman buku "'.$peminjaman->buku->judul.'" dibatalkan otomatis karena tidak diambil sampai '.$peminjaman->tgl_ambil_fix->format('d M Y').'. Kamu dilarang meminjam buku sampai '.$berlakuSampai->format('d M Y').'.',
                'kategori' => 'sanksi',
                'is_read'  => false,
            ]);
        }
    }

    // Helper - Tandai otomatis buku yang sudah lewat tgl_kembali tapi belum dikembalikan,
    // supaya tidak menggantung tanpa sanksi sampai admin sempat klik "Konfirmasi Kembali".
    // Sanksi hanya dibuat SEKALI per peminjaman (dicek dulu supaya tidak dobel setiap halaman dibuka).
    // Buku tetap berstatus 'dipinjam' — baru benar-benar 'selesai' saat admin konfirmasi pengembalian fisiknya.
    private function tandaiKeterlambatanOtomatis(): void
    {
        $telat = Peminjaman::with('buku')
            ->where('status', 'dipinjam')
            ->whereDate('tgl_kembali', '<', Carbon::today())
            ->get();

        foreach ($telat as $peminjaman) {
            $sudahAdaSanksi = Sanksi::where('peminjaman_id', $peminjaman->id)
                ->where('jenis', 'keterlambatan')
                ->exists();

            if ($sudahAdaSanksi) {
                continue;
            }

            $telatHari = $peminjaman->tgl_kembali->diffInDays(Carbon::today());

            Sanksi::create([
                'peminjaman_id' => $peminjaman->id,
                'user_id'       => $peminjaman->user_id,
                'jenis'         => 'keterlambatan',
                'keterangan'    => 'Buku "'.$peminjaman->buku->judul.'" sudah melewati batas waktu pengembalian ('.$peminjaman->tgl_kembali->format('d M Y').'), terlambat '.$telatHari.' hari dan belum dikembalikan. Segera kembalikan buku ke perpustakaan.',
                'status'        => 'menunggu',
            ]);

            Notifikasi::create([
                'user_id'  => $peminjaman->user_id,
                'pesan'    => 'Buku "'.$peminjaman->buku->judul.'" sudah melewati batas waktu pengembalian sejak '.$peminjaman->tgl_kembali->format('d M Y').'. Segera kembalikan ke perpustakaan untuk menghindari sanksi lebih lanjut.',
                'kategori' => 'sanksi',
                'is_read'  => false,
            ]);
        }
    }

    // Helper - Cek apakah anggota masih boleh meminjam (batas 3 aktif & tidak ada sanksi menggantung)
    private function cekBatasPeminjaman($userId): ?string
    {
        // Cek sanksi yang belum selesai diverifikasi (keterlambatan maupun buku rusak)
        $sanksiAktif = Sanksi::where('user_id', $userId)
            ->where('status', '!=', 'selesai')
            ->exists();

        if ($sanksiAktif) {
            return 'Kamu memiliki sanksi yang belum diselesaikan. Selesaikan sanksinya dan tunggu diverifikasi admin sebelum bisa meminjam buku lagi.';
        }

        // Cek larangan sementara karena tidak mengambil buku yang sudah disetujui
        $larangan = Sanksi::where('user_id', $userId)
            ->where('jenis', 'tidak_diambil')
            ->whereDate('berlaku_sampai', '>=', today())
            ->latest('berlaku_sampai')
            ->first();

        if ($larangan) {
            return 'Kamu masih dilarang meminjam buku sampai tanggal '.$larangan->berlaku_sampai->format('d M Y').' karena tidak mengambil buku yang sudah disetujui sebelumnya.';
        }

        // Cek apakah ada buku yang sudah TELAT (lewat tanggal kembali) dan belum dikembalikan
        $adaTelat = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->whereDate('tgl_kembali', '<', today())
            ->exists();

        if ($adaTelat) {
            return 'Kamu memiliki buku yang sudah melewati batas waktu pengembalian dan belum dikembalikan. Kembalikan buku tersebut terlebih dahulu sebelum meminjam buku lain.';
        }

        // Cek jumlah peminjaman yang masih aktif (belum selesai)
        $jumlahAktif = Peminjaman::where('user_id', $userId)
            ->whereIn('status', ['menunggu', 'disetujui', 'menunggu_konfirmasi', 'dipinjam'])
            ->count();

        if ($jumlahAktif >= 3) {
            return 'Kamu sudah mencapai batas maksimal 3 peminjaman aktif. Kembalikan salah satu buku terlebih dahulu sebelum meminjam lagi.';
        }

        return null;
    }





    // Helper - Tambah hari kerja (skip Sabtu & Minggu)
    private function tambahHariKerja($tanggal, $hari)
    {
        $tgl = $tanggal->copy();
        $hitungan = 0;
        while ($hitungan < $hari) {
            $tgl->addDay();
            if (!$tgl->isWeekend()) {
                $hitungan++;
            }
        }
        return $tgl;
    }

// Halaman data peminjaman buku paket
// public function indexBukuPaket(Request $request)
// {
//     $query = \App\Models\PeminjamanPaket::with('buku');

//     if ($request->search) {
//         $query->where(function($q) use ($request) {
//             $q->where('nama_peminjam', 'like', '%'.$request->search.'%')
//               ->orWhere('kelas', 'like', '%'.$request->search.'%')
//               ->orWhereHas('buku', fn($q2) => $q2->where('judul', 'like', '%'.$request->search.'%'));
//         });
//     }

//     if ($request->status) {
//         $query->where('status', $request->status);
//     }

//     $peminjamanPaket = $query->latest()->paginate(15);
//     return view('pengurus.peminjaman.buku-paket', compact('peminjamanPaket'));
// }

// Konfirmasi pengembalian buku paket
// public function kembalikanPaket(Request $request, $id)
// {
//     $peminjaman = \App\Models\PeminjamanPaket::findOrFail($id);
//     $peminjaman->update([
//         'status'           => 'dikembalikan',
//         'tgl_kembali_aktual' => now()->toDateString(),
//     ]);

//     // Kembalikan stok buku
//     $peminjaman->buku->increment('stok', $peminjaman->jumlah_dipinjam);

//     return back()->with('success', 'Pengembalian buku paket dikonfirmasi!');
// }




}