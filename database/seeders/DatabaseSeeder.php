<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Siswa
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => null,
            'nisn'     => '1234567890',
            'role'     => 'siswa',
            'password' => Hash::make('1234567890'),
        ]);

        // Guru
        User::create([
            'name'     => 'Pak Ahmad',
            'email'    => 'ahmad@guru.com',
            'nisn'     => null,
            'role'     => 'guru',
            'password' => Hash::make('ahmad@guru.com'),
        ]);

        // Pengurus
        User::create([
            'name'     => 'Siti Pengurus',
            'email'    => 'siti@pengurus.com',
            'nisn'     => null,
            'role'     => 'pengurus',
            'password' => Hash::make('siti@pengurus.com'),
        ]);

        // Kepala
        User::create([
            'name'     => 'Bu Kepala',
            'email'    => 'kepala@perpus.com',
            'nisn'     => null,
            'role'     => 'kepala',
            'password' => Hash::make('kepala@perpus.com'),
        ]);
    }
}