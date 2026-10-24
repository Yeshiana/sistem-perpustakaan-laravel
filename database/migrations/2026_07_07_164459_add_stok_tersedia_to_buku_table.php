<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->integer('stok_tersedia')->default(0)->after('stok');
        });

        // Backfill: stok_tersedia awal = stok fisik saat ini
        DB::table('buku')->update(['stok_tersedia' => DB::raw('stok')]);
    }

    public function down(): void
    {
        Schema::table('buku', function (Blueprint $table) {
            $table->dropColumn('stok_tersedia');
        });
    }
};