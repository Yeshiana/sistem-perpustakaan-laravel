<?php

namespace App\Http\Controllers;

// use App\Models\Buku;
// use App\Models\Kategori;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\PermintaanEditBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('judul', 'like', '%'.$request->search.'%')
                  ->orWhere('pengarang', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->kategori_id) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku     = $query->latest()->paginate(10);
        $kategori = Kategori::orderBy('nama')->get();

        return view('pengurus.buku.index', compact('buku', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama')->get();
        return view('pengurus.buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategori,id',
            'isbn'         => 'nullable|string|max:100',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer',
            'rak'          => 'nullable|string|max:100',
            'sinopsis'     => 'nullable|string',
            'stok'         => 'required|integer|min:0',
            'cover'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

        Buku::create([
    'judul'         => $request->judul,
    'pengarang'     => $request->pengarang,
    'kategori_id'   => $request->kategori_id,
    'kategori'      => optional(Kategori::find($request->kategori_id))->nama,
    'isbn'          => $request->isbn,
    'penerbit'      => $request->penerbit,
    'tahun_terbit'  => $request->tahun_terbit,
    'rak'           => $request->rak,
    'sinopsis'      => $request->sinopsis,
    'stok'          => $request->stok,
    'stok_tersedia' => $request->stok,   // <-- baris baru
    'cover'         => $coverPath,
]);

        return redirect()->route('pengurus.buku.index')
                         ->with('success', 'Buku berhasil ditambahkan!');
    }

    public function show(Buku $buku)
    {
        $buku->load('kategori');
        return view('pengurus.buku.show', compact('buku'));
    }

    public function edit(Buku $buku)
{
    $kategori = Kategori::orderBy('nama')->get();

    $permintaanAktif = PermintaanEditBuku::where('buku_id', $buku->id)
        ->where('user_id', auth()->id())
        ->where('status', 'disetujui')
        ->whereNull('digunakan_at')
        ->latest()
        ->first();

    $permintaanMenunggu = PermintaanEditBuku::where('buku_id', $buku->id)
        ->where('user_id', auth()->id())
        ->where('status', 'menunggu')
        ->latest()
        ->first();

    $fieldTerkunci = ['judul', 'pengarang', 'kategori_id', 'isbn', 'penerbit', 'tahun_terbit', 'stok', 'cover'];
    $fieldTerbuka  = $permintaanAktif ? $permintaanAktif->field_diminta : [];

    return view('pengurus.buku.edit', compact('buku', 'kategori', 'fieldTerkunci', 'fieldTerbuka', 'permintaanAktif', 'permintaanMenunggu'));
}

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'pengarang'    => 'required|string|max:255',
            'kategori_id'  => 'required|exists:kategori,id',
            'isbn'         => 'nullable|string|max:100',
            'penerbit'     => 'nullable|string|max:255',
            'tahun_terbit' => 'nullable|integer',
            'rak'          => 'nullable|string|max:100',
            'sinopsis'     => 'nullable|string',
            'stok'         => 'required|integer|min:0',
            'cover'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $coverPath = $buku->cover;
        if ($request->hasFile('cover')) {
            if ($buku->cover) Storage::disk('public')->delete($buku->cover);
            $coverPath = $request->file('cover')->store('covers', 'public');
        }

       $selisih = $request->stok - $buku->stok;
    $stokTersediaBaru = max(0, $buku->stok_tersedia + $selisih);


        $buku->update([
    'judul'         => $request->judul,
    'pengarang'     => $request->pengarang,
    'kategori_id'   => $request->kategori_id,
    'kategori'      => optional(Kategori::find($request->kategori_id))->nama,
    'isbn'          => $request->isbn,
    'penerbit'      => $request->penerbit,
    'tahun_terbit'  => $request->tahun_terbit,
    'rak'           => $request->rak,
    'sinopsis'      => $request->sinopsis,
    'stok'          => $request->stok,
    'stok_tersedia' => $stokTersediaBaru,
    'cover'         => $coverPath,
]);

        // Kalau ada izin edit aktif yang barusan dipakai, kunci lagi (1x kesempatan)
        $permintaanAktif = PermintaanEditBuku::where('buku_id', $buku->id)
            ->where('user_id', auth()->id())
            ->where('status', 'disetujui')
            ->whereNull('digunakan_at')
            ->latest()
            ->first();

        if ($permintaanAktif) {
            $permintaanAktif->update(['digunakan_at' => now()]);
        }

        return redirect()->route('pengurus.buku.index')
                         ->with('success', 'Buku berhasil diupdate!');
    }

    public function destroy(Buku $buku)
    {
        if ($buku->cover) Storage::disk('public')->delete($buku->cover);
        $buku->delete();
        return redirect()->route('pengurus.buku.index')
                         ->with('success', 'Buku berhasil dihapus!');
    }
}