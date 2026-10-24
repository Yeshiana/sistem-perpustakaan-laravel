<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tambah kolom kategori_id ke tabel buku
        Schema::table('buku', function (Blueprint $table) {
    $table->unsignedBigInteger('kategori_id')->nullable();
    $table->foreign('kategori_id')->references('id')->on('kategori')->nullOnDelete();
});
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropForeign(['kategori_id']);
            $table->dropColumn('kategori_id');
        });

        Schema::dropIfExists('kategori');
    }
};