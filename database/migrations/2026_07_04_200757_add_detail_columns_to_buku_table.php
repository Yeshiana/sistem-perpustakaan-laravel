<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {

            $table->string('isbn')->nullable()->after('kategori');

            $table->string('penerbit')->nullable()->after('isbn');

            $table->year('tahun_terbit')->nullable()->after('penerbit');

            $table->string('rak')->nullable()->after('tahun_terbit');

            $table->text('sinopsis')->nullable()->after('rak');

        });
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {

            $table->dropColumn([
                'isbn',
                'penerbit',
                'tahun_terbit',
                'rak',
                'sinopsis'
            ]);

        });
    }
};