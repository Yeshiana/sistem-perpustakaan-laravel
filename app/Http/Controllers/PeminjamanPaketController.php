<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanPaket;
use App\Models\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PeminjamanPaketController extends Controller
{
    // Daftar semua peminjaman paket
    public function index(Request $request)
    {
        $query = PeminjamanPaket::with(['buku', 'pengurus']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_peminjam', 'like', '%'.$request->search.'%')
                  ->orWhere('kelas', 'like', '%'.$request->search.'%')
                  ->orWhere('kode_peminjaman', 'like', '%'.$request->search.'%')
                  ->orWhereHas('buku', function($qb) use ($request) {
                      $qb->where('judul', 'like', '%'.$request->search.'%');
                  });
            });
        }

        $paket = $query->latest()->paginate(10);
        return view('pengurus.peminjaman-paket.index', compact('paket'));
    }

    // Form tambah
    public function create()
    {
        return view('pengurus.peminjaman-paket.create');
    }

    // Cari buku (AJAX, dipakai select di form)
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

    // Simpan peminjaman paket baru
    public function store(Request $request)
    {
        $request->validate([
            'buku_id'         => 'required|exists:buku,id',
            'jumlah'          => 'required|integer|min:1',
            'nama_peminjam'   => 'required|string|max:255',
            'kelas'           => 'required|string|max:100',
            'mata_pelajaran'  => 'required|string|max:100',
            'guru'            => 'required|string|max:255',
            'tgl_kembali'     => 'required|date|after_or_equal:today',
        ]);

        $buku = Buku::findOrFail($request->buku_id);

        if ($buku->stok_tersedia < $request->jumlah) {
            return redirect()->back()->withInput()
                ->with('error', 'Stok tersedia tidak mencukupi! Stok saat ini: '.$buku->stok_tersedia);
        }

        $tglPinjam = Carbon::today();

        // Serah terima langsung di tempat, jadi stok fisik & stok tersedia
        // dikurangi sekaligus sejumlah eksemplar yang dipinjam
        $buku->decrement('stok', $request->jumlah);
        $buku->decrement('stok_tersedia', $request->jumlah);

        PeminjamanPaket::create([
            'buku_id'        => $request->buku_id,
            'jumlah'         => $request->jumlah,
            'nama_peminjam'  => $request->nama_peminjam,
            'kelas'          => $request->kelas,
            'mata_pelajaran' => $request->mata_pelajaran,
            'guru'           => $request->guru,
            'pengurus_id'    => auth()->id(),
            'tgl_pinjam'     => $tglPinjam,
            'tgl_kembali'    => $request->tgl_kembali,
            'status'         => 'dipinjam',
        ]);

        return redirect()->route('pengurus.peminjaman-paket.index')
                         ->with('success', 'Peminjaman buku paket berhasil dicatat!');
    }

    // Konfirmasi pengembalian
    public function kembalikan(Request $request, PeminjamanPaket $peminjamanPaket)
    {
        $request->validate([
            'jumlah_kembali' => 'required|integer|min:0|max:'.$peminjamanPaket->jumlah,
            'keterangan'     => 'nullable|string',
        ]);

        $peminjamanPaket->update([
            'status'           => 'selesai',
            'tgl_dikembalikan' => Carbon::today(),
            'jumlah_kembali'   => $request->jumlah_kembali,
            'keterangan'       => $request->keterangan,
        ]);

        // Stok fisik & stok tersedia bertambah sejumlah yang benar-benar dikembalikan.
        // Kalau ada yang hilang/rusak (jumlah_kembali < jumlah), selisihnya tidak ditambahkan
        // kembali ke stok, dan tercatat di kolom keterangan untuk tindak lanjut manual oleh pengurus.
        if ($request->jumlah_kembali > 0) {
            $peminjamanPaket->buku->increment('stok', $request->jumlah_kembali);
            $peminjamanPaket->buku->increment('stok_tersedia', $request->jumlah_kembali);
        }

        return redirect()->back()->with('success', 'Pengembalian buku paket berhasil dikonfirmasi!');
    }
}