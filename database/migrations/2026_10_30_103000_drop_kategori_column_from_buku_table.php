<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom teks lama 'kategori' sudah digantikan oleh relasi kategori_id.
        // Dicek dulu supaya aman dijalankan di database yang kolomnya sudah tidak ada.
        if (Schema::hasColumn('buku', 'kategori')) {
            Schema::table('buku', function (Blueprint $table) {
                $table->dropColumn('kategori');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('buku', 'kategori')) {
            Schema::table('buku', function (Blueprint $table) {
                $table->string('kategori')->nullable()->after('pengarang');
            });
        }
    }
};
