<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->date('tgl_lapor');
            $table->string('bukti_pengembalian_mandiri')->nullable();
            $table->date('tgl_kembali_aktual')->nullable();
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tgl_konfirmasi')->nullable();
            $table->enum('kondisi_buku', ['baik', 'rusak', 'hilang'])->nullable();
            $table->enum('status_keterlambatan', ['tepat_waktu', 'terlambat'])->nullable();
            $table->string('sanksi_kegiatan')->nullable();
            $table->string('bukti_sanksi')->nullable();
            $table->enum('status_sanksi', ['tidak_perlu', 'belum_selesai', 'selesai'])->default('tidak_perlu');
            $table->enum('status_penggantian', ['tidak_perlu', 'menunggu', 'sudah_diganti'])->default('tidak_perlu');
            $table->string('bukti_penggantian')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengembalian');
    }
};