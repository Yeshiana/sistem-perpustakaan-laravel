<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class KunjunganController extends Controller
{
    public function index()
    {
        return view('kunjungan.index');
    }

    public function cariAnggota(Request $request)
    {
        $q = trim($request->query('q', ''));

        if (mb_strlen($q) < 3) {
            return response()->json([]);
        }

        $q = str_replace(['%', '_'], ['\%', '\_'], $q);

        $hasil = User::query()
            ->whereIn('role', ['siswa', 'guru'])
            ->where('name', 'like', '%' . $q . '%')
            ->orderBy('name')
            ->limit(5)
            ->get()
            ->map(fn ($u) => [
                'id'    => $u->id,
                'nama'  => $u->name,
                'kelas' => trim(($u->kelas ?? '') . ' ' . ($u->jurusan ?? '')),
            ]);

        return response()->json($hasil);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'nama_pengunjung' => 'required|string|max:255',
            'tujuan'          => 'required|string|max:255',
            'anggota_id'      => 'nullable|integer',
        ]);

        $user = Auth::user();

        // Kalau tidak login, cek apakah dia memilih anggota dari saran
        if (!$user && $request->filled('anggota_id')) {
            $user = User::whereIn('role', ['siswa', 'guru'])
                ->where('id', $request->anggota_id)
                ->where('name', $request->nama_pengunjung) // nama harus masih sama
                ->first();
        }

        Kunjungan::create([
            'user_id'          => $user?->id,
            'nama_pengunjung'  => $user?->name ?? $request->nama_pengunjung,
            'kelas_pengunjung' => $user
                ? trim(($user->kelas ?? '') . ' ' . ($user->jurusan ?? ''))
                : $request->kelas_pengunjung,
            'jenis_pengunjung' => $user ? 'anggota' : 'umum',
            'tujuan'           => $request->tujuan,
            'tanggal'          => Carbon::today(),
            'jam_masuk'        => Carbon::now()->format('H:i:s'),
        ]);

        return view('kunjungan.sukses', ['nama' => $user?->name ?? $request->nama_pengunjung]);
    }
}