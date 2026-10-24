<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::withCount('buku')->latest()->get();
        return view('pengurus.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('pengurus.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'      => 'required|string|max:255|unique:kategori,nama',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        Kategori::create($request->only('nama', 'deskripsi'));

        return redirect()->route('pengurus.kategori.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}