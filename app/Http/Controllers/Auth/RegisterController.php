<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    // Halaman pilih role
    public function pilihRole()
    {
        return view('auth.pilih-role');
    }

    // Form register siswa
    public function formSiswa()
    {
        return view('auth.register-siswa');
    }

    // Form register guru
    public function formGuru()
    {
        return view('auth.register-guru');
    }


    // Proses register siswa
public function storeSiswa(Request $request)
{
        $request->validate([
        'nisn'        => 'required|digits:10|unique:users,nisn',
        'name'        => 'required|string|max:255',
        'password'    => 'required|string|min:8|confirmed',
        'jurusan'     => 'required|string',
        'kelas'       => 'required|string',
        'tahun_masuk' => 'required|digits:4|integer',
        'ttl'         => 'required|date',
        'alamat'      => 'required|string',
        'foto'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'no_hp' => 'required|string|max:15',
        'email' => 'required|email|unique:users,email',
    ], [
        'nisn.required'        => 'NISN wajib diisi. (NISN field is required.)',
        'nisn.digits'          => 'NISN harus 10 digit angka. (NISN field must be 10 digits.)',
        'nisn.unique'          => 'NISN ini sudah terdaftar. (This NISN has already been taken.)',
        'name.required'        => 'Nama lengkap wajib diisi. (Name field is required.)',
        'password.required'    => 'Password wajib diisi. (Password field is required.)',
        'password.min'         => 'Password minimal 8 karakter. (Password must be at least 8 characters.)',
        'password.confirmed'   => 'Konfirmasi password tidak cocok. (Password confirmation does not match.)',
        'jurusan.required'     => 'Jurusan wajib dipilih. (Jurusan field is required.)',
        'kelas.required'       => 'Kelas wajib dipilih. (Kelas field is required.)',
        'tahun_masuk.required' => 'Tahun masuk wajib dipilih. (Tahun masuk field is required.)',
        'tahun_masuk.digits'   => 'Tahun masuk harus 4 digit angka. (Tahun masuk must be 4 digits.)',
        'ttl.required'         => 'Tanggal lahir wajib diisi. (Date of birth field is required.)',
        'ttl.date'             => 'Format tanggal lahir tidak valid. (Date of birth is not a valid date.)',
        'alamat.required'      => 'Alamat wajib diisi. (Address field is required.)',
        'foto.image'           => 'File foto harus berupa gambar. (Foto field must be an image.)',
        'foto.mimes'           => 'Format foto harus jpg, jpeg, atau png. (Foto field must be a file of type: jpg, jpeg, png.)',
        'foto.max'             => 'Ukuran foto maksimal 2MB. (Foto field must not be greater than 2048 kilobytes.)',
        'no_hp.required'       => 'No HP wajib diisi. (No HP field is required.)',
        'email.required'       => 'Email wajib diisi. (Email field is required.)',
        'email.email'          => 'Format email tidak valid. (Email field must be a valid email address.)',
        'email.unique'         => 'Email ini sudah terdaftar. (This email has already been taken.)',
    ]);
    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('foto_profil', 'public');
    }

    // Hitung tanggal kadaluarsa otomatis (tahun masuk + 3 tahun)
    $tanggalAktifHingga = \Carbon\Carbon::create($request->tahun_masuk, 7, 1)->addYears(3);

    $user = User::create([
        'nisn'                 => $request->nisn,
        'name'                 => $request->name,
        'password'             => Hash::make($request->password),
        'role'                 => 'siswa',
        'jurusan'              => $request->jurusan,
        'kelas'                => $request->kelas,
        'tahun_masuk'          => $request->tahun_masuk,
        'tanggal_aktif_hingga' => $tanggalAktifHingga,
        'ttl'                  => $request->ttl,
        'alamat'               => $request->alamat,
        'foto'                 => $fotoPath,
        'is_active'            => true,
        'no_hp'                => $request->no_hp,
        'email'                => $request->email,

    ]);

        auth()->login($user);
        return redirect()->route('siswa.dashboard')->with('success', 'Akun anggota Anda berhasil dibuat! Selamat datang 🎉');



}
// Proses register guru
public function storeGuru(Request $request)
{
    $request->validate([
        'name'     => 'required|string|max:255',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:8|confirmed',
        'ttl'      => 'required|date',
        'alamat'   => 'required|string',
        'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $fotoPath = null;
    if ($request->hasFile('foto')) {
        $fotoPath = $request->file('foto')->store('foto_profil', 'public');
    }

    $user = User::create([
        'name'      => $request->name,
        'email'     => $request->email,
        'password'  => Hash::make($request->password),
        'role'      => 'guru',
        'ttl'       => $request->ttl,
        'alamat'    => $request->alamat,
        'foto'      => $fotoPath,
        'is_active' => true,
    ]);

        auth()->login($user);
       return redirect()->route('siswa.dashboard')->with('success', 'Akun anggota Anda berhasil dibuat! Selamat datang 🎉');
}
}
