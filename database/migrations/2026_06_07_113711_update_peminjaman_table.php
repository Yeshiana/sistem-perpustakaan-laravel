<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tgl_booking')->nullable()->after('buku_id');
            $table->date('tgl_ambil')->nullable()->after('tgl_booking');
            $table->string('bukti_pengambilan')->nullable()->after('tgl_ambil');
            $table->dropColumn('tgl_pinjam');
            $table->date('tgl_pinjam')->nullable()->after('bukti_pengambilan');
            $table->enum('status', [
                'menunggu',
                'disetujui',
                'menunggu_konfirmasi',
                'dipinjam',
                'selesai',
                'ditolak',
                'dibatalkan'
            ])->default('menunggu')->change();
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['tgl_booking', 'tgl_ambil', 'bukti_pengambilan']);
        });
    }
};