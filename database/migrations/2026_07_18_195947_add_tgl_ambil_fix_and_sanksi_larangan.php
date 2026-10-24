<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tgl_ambil_fix')->nullable()->after('tgl_ambil');
        });

        Schema::table('sanksi', function (Blueprint $table) {
            $table->date('berlaku_sampai')->nullable()->after('bukti_sanksi');
            $table->enum('jenis', ['keterlambatan', 'buku_rusak', 'tidak_diambil'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('tgl_ambil_fix');
        });

        Schema::table('sanksi', function (Blueprint $table) {
            $table->dropColumn('berlaku_sampai');
            $table->enum('jenis', ['keterlambatan', 'buku_rusak'])->change();
        });
    }
};