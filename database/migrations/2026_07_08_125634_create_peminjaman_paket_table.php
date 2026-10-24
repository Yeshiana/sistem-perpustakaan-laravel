<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_paket', function (Blueprint $table) {
            $table->id();
            $table->string('kode_peminjaman')->unique();
            $table->foreignId('buku_id')->constrained('buku')->onDelete('cascade');
            $table->integer('jumlah');
            $table->string('nama_peminjam');
            $table->string('kelas');
            $table->string('mata_pelajaran');
            $table->string('guru');
            $table->foreignId('pengurus_id')->constrained('users')->onDelete('cascade');
            $table->date('tgl_pinjam');
            $table->date('tgl_kembali');
            $table->date('tgl_dikembalikan')->nullable();
            $table->integer('jumlah_kembali')->nullable();
            $table->enum('status', ['dipinjam', 'selesai'])->default('dipinjam');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_paket');
    }
};