<?php

namespace App\Http\Controllers;

use App\Models\PermintaanEditBuku;
use App\Models\Buku;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class PermintaanEditBukuController extends Controller
{
    private array $fieldTerkunci = ['judul', 'pengarang', 'kategori_id', 'isbn', 'penerbit', 'tahun_terbit', 'stok', 'cover'];

    // Admin - Ajukan permintaan edit field terkunci
    public function store(Request $request, Buku $buku)
    {
        $request->validate([
            'field_diminta'   => 'required|array|min:1',
            'field_diminta.*' => 'in:'.implode(',', $this->fieldTerkunci),
            'alasan'          => 'required|string|min:10',
        ]);

        $sudahAda = PermintaanEditBuku::where('buku_id', $buku->id)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['menunggu', 'disetujui'])
            ->whereNull('digunakan_at')
            ->exists();

        if ($sudahAda) {
            return redirect()->back()->with('error', 'Kamu masih punya pengajuan aktif untuk buku ini. Tunggu diproses atau gunakan izin yang sudah ada dulu.');
        }

        PermintaanEditBuku::create([
            'buku_id'       => $buku->id,
            'user_id'       => auth()->id(),
            'field_diminta' => $request->field_diminta,
            'alasan'        => $request->alasan,
            'status'        => 'menunggu',
        ]);

        $semuaKepala = User::where('role', 'kepala')->get();
        foreach ($semuaKepala as $kepala) {
            Notifikasi::create([
                'user_id'  => $kepala->id,
                'pesan'    => auth()->user()->name.' mengajukan izin edit data buku "'.$buku->judul.'". Harap ditinjau.',
                'kategori' => 'peminjaman',
                'is_read'  => false,
            ]);
        }

        return redirect()->back()->with('success', 'Pengajuan edit berhasil dikirim! Menunggu persetujuan Kepala Perpustakaan.');
    }

    // Kepala - Lihat semua pengajuan
    public function index(Request $request)
    {
        $query = PermintaanEditBuku::with(['buku', 'pengaju']);

        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'menunggu'); // default: yang belum diproses dulu
        }

        $permintaan = $query->latest()->paginate(10);
        return view('kepala.permintaan-edit-buku.index', compact('permintaan'));
    }

    // Kepala - Setujui
    public function setujui(PermintaanEditBuku $permintaanEditBuku)
    {
        $permintaanEditBuku->update([
            'status'         => 'disetujui',
            'disetujui_oleh' => auth()->id(),
        ]);

        $fieldLabel = collect($permintaanEditBuku->field_diminta)
            ->map(fn($f) => PermintaanEditBuku::labelField($f))
            ->join(', ');

        Notifikasi::create([
            'user_id'  => $permintaanEditBuku->user_id,
            'pesan'    => 'Pengajuan edit buku "'.$permintaanEditBuku->buku->judul.'" disetujui! Field yang bisa diedit: '.$fieldLabel.'. Silakan buka halaman Edit Buku.',
            'kategori' => 'peminjaman',
            'is_read'  => false,
        ]);

        return redirect()->back()->with('success', 'Pengajuan disetujui.');
    }

    // Kepala - Tolak
    public function tolak(Request $request, PermintaanEditBuku $permintaanEditBuku)
    {
        $permintaanEditBuku->update([
            'status'         => 'ditolak',
            'disetujui_oleh' => auth()->id(),
            'catatan_kepala' => $request->catatan_kepala,
        ]);

        Notifikasi::create([
            'user_id'  => $permintaanEditBuku->user_id,
            'pesan'    => 'Pengajuan edit buku "'.$permintaanEditBuku->buku->judul.'" ditolak.'.($request->catatan_kepala ? ' Alasan: '.$request->catatan_kepala : ''),
            'kategori' => 'peminjaman',
            'is_read'  => false,
        ]);

        return redirect()->back()->with('success', 'Pengajuan ditolak.');
    }
}