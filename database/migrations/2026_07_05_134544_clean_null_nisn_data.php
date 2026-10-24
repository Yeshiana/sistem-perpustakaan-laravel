<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    public function up()
    {
        // Cek dulu apakah index unique 'users_nisn_unique' ada, baru hapus kalau memang ada
        $indexAda = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_nisn_unique'");

        if (count($indexAda) > 0) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_nisn_unique');
            });
        }

        // Isi NISN untuk role yang tidak punya (guru, pengurus, kepala)
        DB::table('users')
            ->whereNull('nisn')
            ->whereIn('role', ['guru', 'pengurus', 'kepala'])
            ->orderBy('id')
            ->get()
            ->each(function ($user) {
                $prefix = match($user->role) {
                    'guru' => 'GRU',
                    'pengurus' => 'PNG',
                    'kepala' => 'KPL',
                    default => 'OTH'
                };
                $nisn = $prefix . str_pad($user->id, 10, '0', STR_PAD_LEFT);

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['nisn' => $nisn]);
            });

        // Hapus data siswa dengan NISN NULL (kalau ada)
        DB::table('users')
            ->whereNull('nisn')
            ->where('role', 'siswa')
            ->delete();

        // Tambahkan lagi UNIQUE constraint, cuma kalau belum ada
        $indexAdaLagi = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_nisn_unique'");

        if (count($indexAdaLagi) === 0) {
            Schema::table('users', function (Blueprint $table) {
                $table->unique('nisn');
            });
        }
    }

    public function down()
    {
        $indexAda = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_nisn_unique'");

        if (count($indexAda) > 0) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_nisn_unique');
            });
        }
    }
};