<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;

// Halaman utama redirect ke login
Route::get('/', function () {
    if (auth()->check()) {
        $role = auth()->user()->role;
        return match($role) {
            'siswa', 'guru' => redirect()->route('siswa.dashboard'),
            'pengurus'      => redirect()->route('pengurus.dashboard'),
            'kepala'        => redirect()->route('kepala.dashboard'),
            default         => view('welcome', [
                'bukuCarousel' => \App\Models\Buku::whereNotNull('cover')->latest()->take(10)->get(),
            ]),
        };
    }
    return view('welcome', [
        'bukuCarousel' => \App\Models\Buku::whereNotNull('cover')->latest()->take(10)->get(),
    ]);
})->name('welcome');
// Route Register Siswa & Guru
// Ganti jadi
Route::get('/daftar', [App\Http\Controllers\Auth\RegisterController::class, 'formSiswa'])->name('register.pilih');
Route::get('/daftar/siswa', [App\Http\Controllers\Auth\RegisterController::class, 'formSiswa'])->name('register.siswa');
Route::get('/daftar/guru', [App\Http\Controllers\Auth\RegisterController::class, 'formGuru'])->name('register.guru');
Route::post('/daftar/siswa', [App\Http\Controllers\Auth\RegisterController::class, 'storeSiswa'])->name('register.siswa.store');
Route::post('/daftar/guru', [App\Http\Controllers\Auth\RegisterController::class, 'storeGuru'])->name('register.guru.store');


// Route Auth (login/logout)
require __DIR__.'/auth.php';

// =====================
// ROUTE SISWA & GURU
// =====================
    Route::middleware(['auth', 'role:siswa,guru'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'siswa'])
    ->name('dashboard');
Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi');
Route::delete('/notifikasi/{notifikasi}', [App\Http\Controllers\NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
Route::delete('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'destroyAll'])->name('notifikasi.destroyAll');

     // Tambahkan ini
   Route::get('/katalog', [App\Http\Controllers\PeminjamanController::class, 'katalog'])->name('katalog');
    Route::post('/pinjam', [App\Http\Controllers\PeminjamanController::class, 'request'])->name('pinjam');
    Route::post('/upload-bukti/{peminjaman}', [App\Http\Controllers\PeminjamanController::class, 'uploadBukti'])->name('upload.bukti');
    Route::get('/riwayat', [App\Http\Controllers\PeminjamanController::class, 'riwayat'])->name('riwayat');

    Route::get('/sanksi', [App\Http\Controllers\SanksiController::class, 'riwayatSanksi'])->name('sanksi');
Route::post('/sanksi/{sanksi}/upload', [App\Http\Controllers\SanksiController::class, 'uploadBukti'])->name('sanksi.upload');
Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'show'])->name('profil');
    });

// =====================
// ROUTE PENGURUS
// =====================
Route::middleware(['auth', 'role:pengurus'])->prefix('pengurus')->name('pengurus.')->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('pengurus.dashboard');
    // })->name('dashboard');
    
    Route::get('/dashboard', [DashboardController::class, 'pengurus'])
    ->name('dashboard');
    Route::resource('anggota', \App\Http\Controllers\AnggotaController::class)
    ->parameters([
        'anggota' => 'anggota'  // ← PASTIKAN PARAMETERNYA 'anggota'
    ]);
    Route::patch('anggota/{anggota}/toggle-aktif',
        [\App\Http\Controllers\AnggotaController::class, 'toggleAktif'])
        ->name('anggota.toggle-aktif');
    Route::resource('buku', \App\Http\Controllers\BukuController::class);
    Route::get('/peminjaman', [App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/peminjaman/offline/create', [App\Http\Controllers\PeminjamanController::class, 'createOffline'])->name('peminjaman.offline.create');
    Route::post('/peminjaman/offline', [App\Http\Controllers\PeminjamanController::class, 'storeOffline'])->name('peminjaman.offline.store');
    Route::get('/peminjaman/offline/cari-anggota', [App\Http\Controllers\PeminjamanController::class, 'cariAnggota'])->name('peminjaman.offline.cari-anggota');
    Route::get('/peminjaman/offline/cari-buku', [App\Http\Controllers\PeminjamanController::class, 'cariBuku'])->name('peminjaman.offline.cari-buku');
    // Peminjaman Buku Paket
Route::get('/peminjaman-paket', [App\Http\Controllers\PeminjamanPaketController::class, 'index'])->name('peminjaman-paket.index');
Route::get('/peminjaman-paket/create', [App\Http\Controllers\PeminjamanPaketController::class, 'create'])->name('peminjaman-paket.create');
Route::post('/peminjaman-paket', [App\Http\Controllers\PeminjamanPaketController::class, 'store'])->name('peminjaman-paket.store');
Route::get('/peminjaman-paket/cari-buku', [App\Http\Controllers\PeminjamanPaketController::class, 'cariBuku'])->name('peminjaman-paket.cari-buku');
Route::patch('/peminjaman-paket/{peminjamanPaket}/kembalikan', [App\Http\Controllers\PeminjamanPaketController::class, 'kembalikan'])->name('peminjaman-paket.kembalikan');
// Route::get('/peminjaman-paket', [App\Http\Controllers\PeminjamanController::class, 'indexBukuPaket'])->name('peminjaman.bukuPaket');


    Route::patch('/peminjaman/{peminjaman}/approve', [App\Http\Controllers\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{peminjaman}/tolak', [App\Http\Controllers\PeminjamanController::class, 'tolak'])->name('peminjaman.tolak');
    Route::patch('/peminjaman/{peminjaman}/konfirmasi-ambil', [App\Http\Controllers\PeminjamanController::class, 'konfirmasiAmbil'])->name('peminjaman.konfirmasi-ambil');
    Route::patch('/peminjaman/{peminjaman}/kembalikan', [App\Http\Controllers\PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

    Route::get('/sanksi', [App\Http\Controllers\SanksiController::class, 'index'])->name('sanksi.index');
    Route::post('/sanksi', [App\Http\Controllers\SanksiController::class, 'store'])->name('sanksi.store');
    Route::patch('/sanksi/{sanksi}/konfirmasi', [App\Http\Controllers\SanksiController::class, 'konfirmasi'])->name('sanksi.konfirmasi');
    Route::patch('/sanksi/{sanksi}/tolak', [App\Http\Controllers\SanksiController::class, 'tolak'])->name('sanksi.tolak');

    Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi');
    Route::delete('/notifikasi/{notifikasi}', [App\Http\Controllers\NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    Route::delete('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'destroyAll'])->name('notifikasi.destroyAll');
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan', [App\Http\Controllers\LaporanController::class, 'store'])->name('laporan.store');

    Route::get('/kategori', [App\Http\Controllers\KategoriController::class, 'index'])->name('kategori.index');
Route::get('/kategori/create', [App\Http\Controllers\KategoriController::class, 'create'])->name('kategori.create');
Route::post('/kategori', [App\Http\Controllers\KategoriController::class, 'store'])->name('kategori.store');
Route::delete('/kategori/{kategori}', [App\Http\Controllers\KategoriController::class, 'destroy'])->name('kategori.destroy');

    Route::get('/profil', [App\Http\Controllers\ProfileController::class, 'editAdmin'])->name('profil');
    Route::patch('/profil', [App\Http\Controllers\ProfileController::class, 'updateAdmin'])->name('profil.update');

    Route::post('/buku/{buku}/ajukan-edit', [App\Http\Controllers\PermintaanEditBukuController::class, 'store'])->name('buku.ajukan-edit');
    });
// =====================
// ROUTE KEPALA
// =====================
    Route::middleware(['auth', 'role:kepala'])->prefix('kepala')->name('kepala.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'kepala'])->name('dashboard');
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'indexKepala'])->name('laporan.index');
    Route::patch('/laporan/{laporan}/acc', [App\Http\Controllers\LaporanController::class, 'acc'])->name('laporan.acc');
    Route::patch('/laporan/{laporan}/tolak', [App\Http\Controllers\LaporanController::class, 'tolak'])->name('laporan.tolak');

    Route::get('/permintaan-edit-buku', [App\Http\Controllers\PermintaanEditBukuController::class, 'index'])->name('permintaan-edit-buku.index');
    Route::patch('/permintaan-edit-buku/{permintaanEditBuku}/setujui', [App\Http\Controllers\PermintaanEditBukuController::class, 'setujui'])->name('permintaan-edit-buku.setujui');
    Route::patch('/permintaan-edit-buku/{permintaanEditBuku}/tolak', [App\Http\Controllers\PermintaanEditBukuController::class, 'tolak'])->name('permintaan-edit-buku.tolak');

    Route::get('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'index'])->name('notifikasi');
    Route::delete('/notifikasi/{notifikasi}', [App\Http\Controllers\NotifikasiController::class, 'destroy'])->name('notifikasi.destroy');
    Route::delete('/notifikasi', [App\Http\Controllers\NotifikasiController::class, 'destroyAll'])->name('notifikasi.destroyAll');
});




Route::get('/keanggotaan-berakhir', function () {
    return view('auth.keanggotaan-berakhir');
})->name('keanggotaan.berakhir');


// =====================
// ROUTE KUNJUNGAN
// =====================
Route::get('/kunjungan', [App\Http\Controllers\KunjunganController::class, 'index'])->name('kunjungan.index');
Route::post('/kunjungan/login', [App\Http\Controllers\KunjunganController::class, 'login'])->name('kunjungan.login');
Route::get('/kunjungan/tujuan', [App\Http\Controllers\KunjunganController::class, 'tujuan'])->name('kunjungan.tujuan');
Route::post('/kunjungan/simpan', [App\Http\Controllers\KunjunganController::class, 'simpan'])->name('kunjungan.simpan');
Route::get('/kunjungan/cari-anggota', [App\Http\Controllers\KunjunganController::class, 'cariAnggota'])
    ->middleware('throttle:30,1')
    ->name('kunjungan.cari-anggota');

