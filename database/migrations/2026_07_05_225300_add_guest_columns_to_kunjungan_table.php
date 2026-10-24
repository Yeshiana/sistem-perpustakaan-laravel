<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            // user_id jadi nullable (pengunjung umum tidak perlu akun)
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // Kolom untuk pengunjung umum
            $table->string('nama_pengunjung')->nullable()->after('user_id');
            $table->string('kelas_pengunjung')->nullable()->after('nama_pengunjung');
            $table->enum('jenis_pengunjung', ['anggota', 'umum'])->default('umum')->after('kelas_pengunjung');
        });
    }

    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropColumn(['nama_pengunjung', 'kelas_pengunjung', 'jenis_pengunjung']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};