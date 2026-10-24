<?php

namespace App\Http\Controllers;

use App\Models\Sanksi;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;

class SanksiController extends Controller
{
    // Admin - lihat semua sanksi (dengan pencarian & filter)
    public function index(Request $request)
    {
        $sanksi = Sanksi::with(['user', 'peminjaman.buku'])
            // Cari berdasarkan nama siswa/guru atau judul buku
            ->when($request->filled('search'), function ($query) use ($request) {
                $kata = $request->search;
                $query->where(function ($q) use ($kata) {
                    $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$kata}%"))
                      ->orWhereHas('peminjaman.buku', fn ($b) => $b->where('judul', 'like', "%{$kata}%"));
                });
            })
            // Filter berdasarkan status sanksi
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            // Filter berdasarkan jenis sanksi
            ->when($request->filled('jenis'), fn ($q) => $q->where('jenis', $request->jenis))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pengurus.sanksi.index', compact('sanksi'));
    }

    // Admin - konfirmasi bukti sanksi
    public function konfirmasi(Sanksi $sanksi)
    {
        $sanksi->update(['status' => 'selesai']);
        // BLOK BARU: kalau sanksi ini karena buku rusak, sekarang baru stok ditambahkan
    // (buku pengganti sudah diverifikasi diterima admin)
    if ($sanksi->jenis == 'buku_rusak') {
        $buku = $sanksi->peminjaman->buku;
        $buku->increment('stok');
        $buku->increment('stok_tersedia');
    }


        Notifikasi::create([
            'user_id' => $sanksi->user_id,
            'pesan'   => 'Sanksi kamu untuk buku "'.$sanksi->peminjaman->buku->judul.'" telah dikonfirmasi selesai. Terima kasih!',
            'kategori' => 'sanksi',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Sanksi dikonfirmasi selesai!');
    }

    // Admin - tolak bukti sanksi
    public function tolak(Sanksi $sanksi)
    {
        $sanksi->update([
            'status'      => 'menunggu',
            'bukti_sanksi' => null,
        ]);

        Notifikasi::create([
            'user_id' => $sanksi->user_id,
            'pesan'   => 'Bukti sanksi kamu untuk buku "'.$sanksi->peminjaman->buku->judul.'" ditolak. Harap upload ulang bukti yang valid.',
            'kategori' => 'sanksi',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Bukti sanksi ditolak!');
    }

    // Admin - tambah sanksi buku rusak manual
    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'keterangan'    => 'nullable|string',
        ]);

        $peminjaman = \App\Models\Peminjaman::find($request->peminjaman_id);

        Sanksi::create([
            'peminjaman_id' => $request->peminjaman_id,
            'user_id'       => $peminjaman->user_id,
            'jenis'         => 'buku_rusak',
            'keterangan'    => $request->keterangan ?? 'Buku dikembalikan dalam kondisi rusak. Harap ganti dengan buku serupa.',
            'status'        => 'menunggu',
        ]);

        Notifikasi::create([
            'user_id' => $peminjaman->user_id,
            'pesan'   => 'Kamu mendapat sanksi buku rusak untuk buku "'.$peminjaman->buku->judul.'". Harap ganti dengan buku serupa dan upload buktinya.',
            'kategori' => 'sanksi',
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', 'Sanksi buku rusak berhasil ditambahkan!');
    }

    // Siswa - lihat sanksi
    public function riwayatSanksi()
    {
        $sanksi = Sanksi::with(['peminjaman.buku'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);
        return view('siswa.sanksi', compact('sanksi'));
    }

    // Siswa - upload bukti sanksi
    public function uploadBukti(Request $request, Sanksi $sanksi)
    {
        $request->validate([
            'bukti_sanksi' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('bukti_sanksi')->store('bukti_sanksi', 'public');

        $sanksi->update([
            'bukti_sanksi' => $path,
            'status'       => 'menunggu_verifikasi',
        ]);

        $semuaPengurus = User::where('role', 'pengurus')->get();
foreach ($semuaPengurus as $pengurus) {
    Notifikasi::create([
        'user_id'  => $pengurus->id,
        'pesan'    => auth()->user()->name.' telah mengupload bukti sanksi untuk buku "'.$sanksi->peminjaman->buku->judul.'". Harap diverifikasi.',
        'kategori' => 'sanksi',
        'is_read'  => false,
    ]);
}

        return redirect()->back()->with('success', 'Bukti sanksi berhasil diupload! Tunggu verifikasi pengurus.');
    }
}