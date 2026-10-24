<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['siswa', 'guru']);

        if ($request->search) {
            $query->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('nisn', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $anggota = $query->latest()->paginate(10);
        return view('pengurus.anggota.index', compact('anggota'));
    }

    public function create()
    {
        return view('pengurus.anggota.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'role'     => 'required|in:siswa,guru',
            'password' => 'required|string|min:8|confirmed',
            'ttl'      => 'nullable|date',
            'alamat'   => 'nullable|string',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($request->role == 'siswa') {
            $rules['nisn']        = 'required|string|unique:users,nisn';
            $rules['no_hp']       = 'required|string|max:15';
            $rules['email']       = 'required|email|unique:users,email';
            $rules['jurusan']     = 'required|string';
            $rules['kelas']       = 'required|string';
            $rules['tahun_masuk'] = 'required|digits:4|integer';
        } else {
            $rules['email'] = 'required|email|unique:users,email';
        }

        $request->validate($rules);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('foto_profil', 'public');
        }

        // Hitung aktif hingga berdasarkan kelas
        $tanggalAktifHingga = null;
        if ($request->role == 'siswa') {
            $tanggalAktifHingga = $this->hitungAktifHingga($request->kelas);
        }

        User::create([
            'name'                 => $request->name,
            'role'                 => $request->role,
            'password' => $request->password,
            'nisn'                 => $request->nisn,
            'no_hp'                => $request->no_hp,
            'email'                => $request->email,
            'jurusan'              => $request->jurusan,
            'kelas'                => $request->kelas,
            'tahun_masuk'          => $request->tahun_masuk,
            'tanggal_aktif_hingga' => $tanggalAktifHingga,
            'ttl'                  => $request->ttl,
            'alamat'               => $request->alamat,
            'foto'                 => $fotoPath,
            'is_active'            => true,
        ]);

        return redirect()->route('pengurus.anggota.index')
                         ->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function show(User $anggota)
    {
        return view('pengurus.anggota.show', compact('anggota'));
    }

    public function edit(User $anggota)
    {
        return view('pengurus.anggota.edit', compact('anggota'));
    }

    public function update(Request $request, User $anggota)
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'ttl'      => 'nullable|date',
            'alamat'   => 'nullable|string',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        if ($anggota->role == 'siswa') {
            $rules['jurusan'] = 'required|string';
            $rules['kelas']   = 'required|string';
            $rules['no_hp']   = 'nullable|string|max:15';
        } elseif ($anggota->role == 'guru') {
            $rules['jabatan'] = 'required|string|max:255';
        }

        $request->validate($rules);

        $fotoPath = $anggota->foto;
        if ($request->hasFile('foto')) {
            if ($anggota->foto) Storage::disk('public')->delete($anggota->foto);
            $fotoPath = $request->file('foto')->store('foto_profil', 'public');
        }

        // Hitung ulang aktif hingga berdasarkan kelas yang diupdate
        $tanggalAktifHingga = $anggota->tanggal_aktif_hingga;
        if ($anggota->role == 'siswa' && $request->kelas) {
            $tanggalAktifHingga = $this->hitungAktifHingga($request->kelas);
        }

        $dataUpdate = [
            'name'                 => $request->name,
            'jurusan'              => $request->jurusan,
            'kelas'                => $request->kelas,
            'no_hp'                => $request->no_hp,
            'jabatan'              => $request->jabatan,
            'ttl'                  => $request->ttl,
            'alamat'               => $request->alamat,
            'foto'                 => $fotoPath,
            'tanggal_aktif_hingga' => $tanggalAktifHingga,
        ];

        $pesanSukses = 'Data anggota berhasil diupdate!';

        // Reset password hanya jika field-nya diisi admin
        if ($request->filled('password')) {
            $dataUpdate['password'] = $request->password;
            $pesanSukses = 'Data anggota berhasil diupdate, dan password baru berhasil disimpan!';

            \App\Models\Notifikasi::create([
                'user_id'  => $anggota->id,
                'pesan'    => 'Password akunmu telah direset oleh pengurus perpustakaan. Jika kamu tidak meminta ini, segera hubungi pengurus.',
                'kategori' => 'peminjaman',
                'is_read'  => false,
            ]);
        }

        $anggota->update($dataUpdate);

        return redirect()->route('pengurus.anggota.show', $anggota)
                         ->with('success', $pesanSukses);
    }

    public function destroy(User $anggota)
    {
        if ($anggota->foto) Storage::disk('public')->delete($anggota->foto);
        $anggota->delete();
        return redirect()->route('pengurus.anggota.index')
                         ->with('success', 'Anggota berhasil dihapus!');
    }

    public function toggleAktif(User $anggota)
    {
        $anggota->update(['is_active' => !$anggota->is_active]);
        $status = $anggota->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('pengurus.anggota.index')
                         ->with('success', "Akun anggota berhasil $status!");
    }

    /**
     * Hitung tanggal aktif hingga berdasarkan kelas saat ini
     * X  → sisa 2 tahun → Juli (tahun ini + 2)
     * XI → sisa 1 tahun → Juli (tahun ini + 1)
     * XII → lulus tahun ini → Juli (tahun ini)
     */
    private function hitungAktifHingga(?string $kelas): ?\Carbon\Carbon
    {
        if (!$kelas) return null;

        $kelasMap = [
            'X'   => 10, 'XI'  => 11, 'XII' => 12,
            '10'  => 10, '11'  => 11, '12'  => 12,
        ];

        $angka = $kelasMap[strtoupper(trim($kelas))] ?? null;

        // Coba extract dari string seperti "XI TKJ", "Kelas 11"
        if (!$angka) {
            preg_match('/\b(XII|XI|X|12|11|10)\b/i', $kelas, $matches);
            $raw   = strtoupper($matches[1] ?? '');
            $angka = $kelasMap[$raw] ?? null;
        }

        if (!$angka) return null;

        $sisaTahun = 12 - $angka; // X=2, XI=1, XII=0
        return \Carbon\Carbon::create(now()->year + $sisaTahun, 7, 1);
    }
}