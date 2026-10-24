<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->year('tahun_masuk')->nullable()->after('foto');
            $table->date('tanggal_aktif_hingga')->nullable()->after('tahun_masuk');
            $table->boolean('is_active')->default(true)->after('tanggal_aktif_hingga');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tahun_masuk', 'tanggal_aktif_hingga', 'is_active']);
        });
    }
};