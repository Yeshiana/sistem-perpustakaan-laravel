<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Cek dulu apakah unique index sudah ada, kalau belum ada baru sekalian ditambahkan
        $indexAda = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_nisn_unique'");

        if (count($indexAda) > 0) {
            // Index sudah ada (dari migration sebelumnya), cukup ubah tipe data & NOT NULL saja
            Schema::table('users', function (Blueprint $table) {
                $table->string('nisn', 10)->nullable(false)->change();
            });
        } else {
            // Index belum ada, sekalian tambahkan unique
            Schema::table('users', function (Blueprint $table) {
                $table->string('nisn', 10)->unique()->nullable(false)->change();
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nisn', 255)->nullable()->change();
        });

        $indexAda = DB::select("SHOW INDEX FROM users WHERE Key_name = 'users_nisn_unique'");
        if (count($indexAda) > 0) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_nisn_unique');
            });
        }
    }
};