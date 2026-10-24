<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::table('kunjungan', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            $table->string('nama')->nullable()->after('user_id');
            $table->string('tujuan', 100)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropColumn('nama');
        });
    }
};