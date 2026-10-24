<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    // Lihat semua notifikasi milik user yang login
    public function index(Request $request)
{
    $query = Notifikasi::where('user_id', auth()->id());

    if ($request->kategori) {
        $query->where('kategori', $request->kategori);
    }

    $notifikasi = $query->latest()->take(10)->get();

    Notifikasi::where('user_id', auth()->id())
        ->where('is_read', false)
        ->update(['is_read' => true]);

    $view = match(auth()->user()->role) {
    'pengurus' => 'pengurus.notifikasi',
    'kepala'   => 'kepala.notifikasi',
    default    => 'siswa.notifikasi',
};

return view($view, compact('notifikasi'));
}
    // Hapus satu notifikasi
    public function destroy(Notifikasi $notifikasi)
    {
        $notifikasi->delete();
        return redirect()->back()->with('success', 'Notifikasi dihapus!');
    }

    // Hapus semua notifikasi
    public function destroyAll()
    {
        Notifikasi::where('user_id', auth()->id())->delete();
        return redirect()->back()->with('success', 'Semua notifikasi dihapus!');
    }
}