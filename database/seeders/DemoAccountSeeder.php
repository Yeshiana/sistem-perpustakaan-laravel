<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Membuat akun demo untuk setiap peran.
 * Aman dijalankan berulang kali (tidak membuat akun ganda).
 *
 * Login memakai "username":
 *  - Siswa                  : NISN
 *  - Guru, Pengurus, Kepala : nama (kolom name)
 */
class DemoAccountSeeder extends Seeder
{
    private const PASSWORD = 'demo12345';

    public function run(): void
    {
        // Akun demo berpassword sederhana, jangan sampai ikut ke server publik.
        if (app()->environment('production')) {
            $this->command?->warn('DemoAccountSeeder dilewati karena environment production.');
            return;
        }

        $akun = [
            [
                'name'                 => 'Siswa Demo',
                'email'                => 'siswa.demo@example.com',
                'nisn'                 => '0000000001',
                'no_hp'                => '081200000001',
                'role'                 => 'siswa',
                'jurusan'              => 'Rekayasa Perangkat Lunak',
                'kelas'                => 'XII RPL 1',
                'ttl'                  => '2008-01-01',
                'alamat'               => 'Alamat contoh (data dummy)',
                'tahun_masuk'          => 2024,
                'tanggal_aktif_hingga' => now()->addYears(3)->toDateString(),
            ],
            [
                'name'    => 'Guru Demo',
                'email'   => 'guru.demo@example.com',
                'no_hp'   => '081200000002',
                'role'    => 'guru',
                'jabatan' => 'Guru',
            ],
            [
                'name'    => 'Petugas Demo',
                'email'   => 'petugas.demo@example.com',
                'no_hp'   => '081200000003',
                'role'    => 'pengurus',
                'jabatan' => 'Petugas Perpustakaan',
            ],
            [
                'name'    => 'Kepala Demo',
                'email'   => 'kepala.demo@example.com',
                'no_hp'   => '081200000004',
                'role'    => 'kepala',
                'jabatan' => 'Kepala Perpustakaan',
            ],
        ];

        foreach ($akun as $data) {
            // Password cukup teks biasa: model User otomatis meng-hash-nya.
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                $data + ['password' => self::PASSWORD, 'is_active' => true]
            );

            // email_verified_at tidak ada di $fillable, jadi diisi terpisah.
            $user->forceFill(['email_verified_at' => now()])->save();
        }
    }
}
