<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Kunjungan;
use App\Models\Laporan;
use App\Models\User;
use App\Models\Sanksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filterType   = $request->filter_type ?? 'bulan';
        $jenisLaporan = $request->jenis_laporan ?? 'kunjungan';
        $bulan        = (int) ($request->bulan ?? Carbon::now()->month);
        $tahun        = (int) ($request->tahun ?? Carbon::now()->year);    

        if ($filterType == 'bulan') {
            $startDate = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $endDate   = Carbon::create($tahun, $bulan, 1)->endOfMonth();
        } else {
            $startDate = Carbon::create($tahun, 1, 1)->startOfYear();
            $endDate   = Carbon::create($tahun, 12, 31)->endOfYear();
        }

        $totalKunjungan   = Kunjungan::whereBetween('tanggal', [$startDate, $endDate])->count();
        $kunjunganSiswa   = Kunjungan::whereBetween('tanggal', [$startDate, $endDate])->whereHas('user', fn($q) => $q->where('role', 'siswa'))->count();
        $kunjunganGuru    = Kunjungan::whereBetween('tanggal', [$startDate, $endDate])->whereHas('user', fn($q) => $q->where('role', 'guru'))->count();
        $kunjunganTujuan  = Kunjungan::whereBetween('tanggal', [$startDate, $endDate])->selectRaw('tujuan, COUNT(*) as total')->groupBy('tujuan')->get();

        $totalPeminjaman      = Peminjaman::whereBetween('tgl_booking', [$startDate, $endDate])->count();
        $peminjamanDisetujui  = Peminjaman::whereBetween('tgl_booking', [$startDate, $endDate])->whereIn('status', ['disetujui', 'dipinjam', 'selesai'])->count();
        $peminjamanDitolak    = Peminjaman::whereBetween('tgl_booking', [$startDate, $endDate])->where('status', 'ditolak')->count();
        $peminjamanDibatalkan = Peminjaman::whereBetween('tgl_booking', [$startDate, $endDate])->where('status', 'dibatalkan')->count();

        $totalPengembalian = Peminjaman::whereBetween('tgl_booking', [$startDate, $endDate])->where('status', 'selesai')->count();

$terlambat = Sanksi::where('jenis', 'keterlambatan')
    ->whereHas('peminjaman', function($q) use ($startDate, $endDate) {
        $q->whereBetween('tgl_booking', [$startDate, $endDate]);
    })
    ->count();

$tepatWaktu = $totalPengembalian - $terlambat;

        $totalBuku       = Buku::count();
        $bukuPerKategori = Buku::with('kategori')
                    ->selectRaw('kategori_id, COUNT(*) as total, SUM(stok) as total_stok')
                    ->groupBy('kategori_id')
                    ->get()
                    ->map(function($item) {
                        return (object)[
                            'kategori'   => $item->kategori?->nama ?? 'Tanpa Kategori',
                            'total'      => $item->total,
                            'total_stok' => $item->total_stok,
                        ];
                    });
        $bukuBaru        = Buku::whereBetween('created_at', [$startDate, $endDate])->count();

        $grafikKunjungan  = Kunjungan::whereBetween('tanggal', [$startDate, $endDate])->selectRaw('DATE(tanggal) as tgl, COUNT(*) as total')->groupBy('tgl')->orderBy('tgl')->get();
        $grafikPeminjaman = Peminjaman::whereBetween('tgl_booking', [$startDate, $endDate])->selectRaw('DATE(tgl_booking) as tgl, COUNT(*) as total')->groupBy('tgl')->orderBy('tgl')->get();


    // TAMBAHAN KODE NENG CLAUDE
    // Detail untuk tabel Peminjaman + Pengembalian + Sanksi
$peminjamanDetail = Peminjaman::with(['user', 'buku', 'sanksi'])
    ->whereBetween('tgl_booking', [$startDate, $endDate])
    ->orderBy('tgl_booking')
    ->get();

// Detail untuk tabel Kunjungan
$kunjunganDetail = Kunjungan::whereBetween('tanggal', [$startDate, $endDate])
    ->orderBy('tanggal')
    ->get();

// Detail untuk tabel Stok Buku
$bukuDetail = Buku::with('kategori')->orderBy('judul')->get();


        // Laporan yang sudah dikirim ke kepala
        // Laporan yang sudah dikirim ke kepala
$laporanTerkirim = Laporan::where('pengurus_id', auth()->id())->latest()->get();

// Untuk kop surat & tanda tangan
$kepalaPerpus = User::where('role', 'kepala')->first();
$kepalaPerpus = \App\Models\User::where('role', 'kepala')->first();

return view('pengurus.laporan.index', compact(
    'filterType', 'jenisLaporan', 'bulan', 'tahun',
    'totalKunjungan', 'kunjunganSiswa', 'kunjunganGuru', 'kunjunganTujuan',
    'totalPeminjaman', 'peminjamanDisetujui', 'peminjamanDitolak', 'peminjamanDibatalkan',
    'totalPengembalian', 'tepatWaktu', 'terlambat',
    'totalBuku', 'bukuPerKategori', 'bukuBaru',
    'grafikKunjungan', 'grafikPeminjaman', 'laporanTerkirim',
    'kepalaPerpus',
    'peminjamanDetail', 'kunjunganDetail', 'bukuDetail'   // ⬅️ baris baru
));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'keterangan'   => 'nullable|string',
            'file_laporan' => 'required|file|mimes:pdf,doc,docx,xlsx|max:5120',
        ]);

        $kepala = User::where('role', 'kepala')->first();

        if (!$kepala) {
            return back()->withErrors(['kepala' => 'Kepala perpustakaan belum terdaftar di sistem.']);
        }

        $filePath = $request->file('file_laporan')->store('laporan', 'public');

        Laporan::create([
            'pengurus_id'  => auth()->id(),
            'kepala_id'    => $kepala->id,
            'judul'        => $request->judul,
            'keterangan'   => $request->keterangan,
            'file_laporan' => $filePath,
            'status'       => 'menunggu',
        ]);

        return back()->with('success', 'Laporan berhasil dikirim ke Kepala Perpustakaan!');
    }

    // Halaman kepala lihat laporan
    public function indexKepala()
    {
        $laporan = Laporan::with('pengurus')->latest()->get();
        return view('kepala.laporan.index', compact('laporan'));
    }

    public function acc(Request $request, Laporan $laporan)
    {
        $laporan->update([
            'status'   => 'disetujui',
            'kepala_id' => auth()->id(),
        ]);
        return back()->with('success', 'Laporan berhasil di-ACC!');
    }

    public function tolak(Request $request, Laporan $laporan)
    {
        $request->validate(['koreksi' => 'required|string']);
        $laporan->update([
            'status'   => 'ditolak',
            'koreksi'  => $request->koreksi,
            'kepala_id' => auth()->id(),
        ]);
        return back()->with('success', 'Laporan telah ditolak dengan catatan koreksi.');
    }
}