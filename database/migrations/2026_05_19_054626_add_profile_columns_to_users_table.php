<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('jurusan')->nullable()->after('nisn');
            $table->string('kelas')->nullable()->after('jurusan');
            $table->string('jabatan')->nullable()->after('kelas');
            $table->date('ttl')->nullable()->after('jabatan');
            $table->text('alamat')->nullable()->after('ttl');
            $table->string('foto')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jurusan', 'kelas', 'jabatan', 'ttl', 'alamat', 'foto']);
        });
    }
};