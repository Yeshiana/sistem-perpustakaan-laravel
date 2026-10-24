<?php

namespace App\Models;
use App\Traits\GeneratesCode;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, GeneratesCode;

    protected $fillable = [
    'name',
    'email',
    'nisn',
    'no_hp',
    'role',
    'password',
    'jurusan',
    'kelas',
    'jabatan',
    'ttl',
    'alamat',
    'foto',
    'tahun_masuk',
    'tanggal_aktif_hingga',
    'is_active',
    'kode_anggota',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function kodeRole(string $role): string
    {
        return match ($role) {
            'siswa'    => 'SIS',
            'guru'     => 'GRU',
            'pengurus' => 'PNG',
            'kepala'   => 'KPL',
            default    => 'ANG',
        };
    }


    protected static function boot()
{
    parent::boot();

    static::creating(function (User $user) {
        if (empty($user->kode_anggota)) {
            $kodeTengah = self::kodeRole($user->role);
            $user->kode_anggota = self::generateKode(
                'kode_anggota',
                'ANG',
                $kodeTengah,
                now()
            );
        }

        // Kolom nisn di database bersifat NOT NULL untuk semua role,
        // padahal cuma siswa yang benar-benar memiliki NISN asli.
        // Untuk guru/pengurus/kepala, generate NISN "placeholder" unik
        // supaya insert tidak gagal — tidak mengubah perilaku untuk siswa.
        if (empty($user->nisn) && $user->role !== 'siswa') {
            $prefix = match($user->role) {
                'guru'     => 'GRU',
                'pengurus' => 'PNG',
                'kepala'   => 'KPL',
                default    => 'OTH',
            };

            do {
                $angka = str_pad((string) random_int(0, 9999999), 7, '0', STR_PAD_LEFT);
                $kandidat = $prefix . $angka;
            } while (self::where('nisn', $kandidat)->exists());

            $user->nisn = $kandidat;
        }
    });
}

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function (User $user) {
    //         if (empty($user->kode_anggota)) {
    //             $kodeTengah = self::kodeRole($user->role);
    //             $user->kode_anggota = self::generateKode(
    //                 'kode_anggota',
    //                 'ANG',
    //                 $kodeTengah,
    //                 now()
    //             );
    //         }
    //     });
    // }
}