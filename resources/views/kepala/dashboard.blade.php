<x-app-layout>

@if(session('success'))
<div id="successPopup" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-sm w-full text-center">
        <div class="text-5xl mb-4">🎉</div>
        <h2 class="text-xl font-bold text-gray-800 mb-2">Berhasil!</h2>
        <p class="text-gray-600 mb-6">{{ session('success') }}</p>
        <button onclick="document.getElementById('successPopup').style.display='none'"
            class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">
            Oke, Lanjutkan!
        </button>
    </div>
    <div class="fixed inset-0 bg-black opacity-40 -z-10"></div>
</div>
@endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Kepala Perpustakaan</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- HERO --}}
            <div class="bg-gradient-to-r from-sky-700 via-sky-600 to-blue-500 rounded-2xl p-8 shadow-lg flex items-center justify-between">
                <div>
                    <p class="text-sky-200 text-sm font-medium mb-1">Selamat Datang 👋</p>
                    <h1 class="text-2xl font-bold text-white">{{ auth()->user()->name }}</h1>
                    <p class="text-sky-100 text-sm mt-1">Kepala Perpustakaan — SMK Negeri 1 Bojongpicung</p>
                    <p class="text-sky-200 text-xs mt-3">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
                <div class="hidden sm:block">
                    <div class="w-20 h-20 rounded-2xl bg-white/20 border-2 border-white/30 flex items-center justify-center text-3xl font-bold text-white">
                        {{ collect(explode(' ', auth()->user()->name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('') }}
                    </div>
                </div>
            </div>

            {{-- STATISTIK --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Buku</p>
                        <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.966 8.966 0 0 0-6 2.292m0-14.25v14.25"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalBuku }}</p>
                    <p class="text-xs text-gray-400 mt-1">Koleksi buku</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Anggota Aktif</p>
                        <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 0 0-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 0 1 5.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 0 1 9.288 0M15 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalAnggota }}</p>
                    <p class="text-xs text-gray-400 mt-1">Siswa & guru</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Kunjungan Hari Ini</p>
                        <div class="w-9 h-9 bg-yellow-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalKunjungan }}</p>
                    <p class="text-xs text-gray-400 mt-1">Kunjungan hari ini</p>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Buku Dipinjam</p>
                        <div class="w-9 h-9 bg-purple-50 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPinjam }}</p>
                    <p class="text-xs text-gray-400 mt-1">Sedang dipinjam</p>
                </div>

            </div>

            {{-- LAPORAN TERBARU --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">Laporan Masuk</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Laporan dari pengurus perpustakaan</p>
                    </div>
                    @if($laporanMenunggu > 0)
                        <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full">
                            {{ $laporanMenunggu }} menunggu ACC
                        </span>
                    @else
                        <span class="bg-green-100 text-green-600 text-xs font-semibold px-3 py-1 rounded-full">
                            Semua sudah di-ACC
                        </span>
                    @endif
                </div>

                @if($laporanTerbaru->isEmpty())
                    <div class="py-12 text-center">
                        <svg class="w-12 h-12 text-gray-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                        </svg>
                        <p class="text-gray-400 text-sm">Belum ada laporan masuk</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50">
                        @foreach($laporanTerbaru as $laporan)
                        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                            <div class="flex items-center gap-4">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0
                                    {{ $laporan->status == 'menunggu' ? 'bg-yellow-50' : ($laporan->status == 'disetujui' ? 'bg-green-50' : 'bg-red-50') }}">
                                    <svg class="w-5 h-5 {{ $laporan->status == 'menunggu' ? 'text-yellow-500' : ($laporan->status == 'disetujui' ? 'text-green-500' : 'text-red-500') }}"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $laporan->judul }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Oleh {{ $laporan->pengurus->name ?? 'Pengurus' }} ·
                                        {{ $laporan->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs font-semibold px-2.5 py-1 rounded-full
                                    {{ $laporan->status == 'menunggu' ? 'bg-yellow-100 text-yellow-700' :
                                       ($laporan->status == 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                    {{ ucfirst($laporan->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>