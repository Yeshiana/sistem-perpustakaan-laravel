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
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Petugas
        </h2>
    </x-slot>

<div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">

            {{-- SAMBUTAN --}}
                <div class="bg-gradient-to-r from-sky-600 to-indigo-600 rounded-2xl shadow-sm p-4 sm:p-6 text-white">
                <p class="text-sky-100 text-xs sm:text-sm">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
                <h2 class="text-base sm:text-xl font-bold mt-1">Selamat datang, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-sky-100 text-xs sm:text-sm mt-1">Berikut ringkasan aktivitas perpustakaan hari ini.</p>
            </div>
            {{-- PERLU TINDAKAN --}}
            @if($menungguPersetujuan > 0 || $sanksiBelumSelesai > 0 || $terlambat > 0)
                        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-3 sm:p-5">
                <div class="flex items-center gap-2 mb-2.5 sm:mb-3">
                    <span class="text-base sm:text-lg">⚡</span>
                    <h3 class="font-semibold text-amber-800 text-xs sm:text-sm">Perlu Tindakan Segera</h3>
                </div>
                <div class="flex flex-col sm:flex-row sm:flex-wrap gap-2 sm:gap-3">
                    @if($menungguPersetujuan > 0)
                    <a href="{{ route('pengurus.peminjaman.index') }}"
                       class="flex items-center gap-2 bg-white border border-amber-200 rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 hover:shadow-sm transition">
                        <span class="text-base sm:text-xl shrink-0">📋</span>
                        <span class="text-xs sm:text-sm text-gray-700"><b class="text-amber-700">{{ $menungguPersetujuan }}</b> peminjaman menunggu persetujuan</span>
                    </a>
                    @endif
                    @if($sanksiBelumSelesai > 0)
                    <a href="{{ route('pengurus.sanksi.index') }}"
                       class="flex items-center gap-2 bg-white border border-amber-200 rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 hover:shadow-sm transition">
                        <span class="text-base sm:text-xl shrink-0">⚠️</span>
                        <span class="text-xs sm:text-sm text-gray-700"><b class="text-amber-700">{{ $sanksiBelumSelesai }}</b> sanksi belum tuntas</span>
                    </a>
                    @endif
                    @if($terlambat > 0)
                    <a href="{{ route('pengurus.peminjaman.index') }}"
                       class="flex items-center gap-2 bg-white border border-amber-200 rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 hover:shadow-sm transition">
                        <span class="text-base sm:text-xl shrink-0">⏰</span>
                        <span class="text-xs sm:text-sm text-gray-700"><b class="text-amber-700">{{ $terlambat }}</b> buku terlambat dikembalikan</span>
                    </a>
                    @endif
                </div>
            </div>
            @endif

            {{-- KARTU STATISTIK UTAMA --}}
                        <div>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-500 mb-2 sm:mb-3">Ringkasan Umum</h3>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-xs sm:text-sm">Total Buku</p>
                                <h2 class="text-xl sm:text-3xl font-bold mt-1 text-gray-800">{{ $totalBuku }}</h2>
                                <p class="text-[10px] sm:text-xs text-gray-400 mt-1">{{ $totalKategori }} kategori</p>
                            </div>
                            <div class="w-8 h-8 sm:w-11 sm:h-11 bg-sky-50 rounded-xl flex items-center justify-center text-base sm:text-xl shrink-0">📖</div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-xs sm:text-sm">Total Anggota</p>
                                <h2 class="text-xl sm:text-3xl font-bold mt-1 text-gray-800">{{ $totalAnggota }}</h2>
                                <p class="text-[10px] sm:text-xs text-gray-400 mt-1">siswa &amp; guru</p>
                            </div>
                            <div class="w-8 h-8 sm:w-11 sm:h-11 bg-indigo-50 rounded-xl flex items-center justify-center text-base sm:text-xl shrink-0">👥</div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-5">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-xs sm:text-sm">Buku Dipinjam</p>
                                <h2 class="text-xl sm:text-3xl font-bold mt-1 text-gray-800">{{ $bukuDipinjam }}</h2>
                                <p class="text-[10px] sm:text-xs text-gray-400 mt-1">+{{ $paketDipinjam }} peminjaman paket</p>
                            </div>
                            <div class="w-8 h-8 sm:w-11 sm:h-11 bg-purple-50 rounded-xl flex items-center justify-center text-base sm:text-xl shrink-0">📚</div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-sky-500 to-indigo-600 rounded-2xl shadow-sm p-3 sm:p-5 text-white">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sky-100 text-xs sm:text-sm">Kunjungan Hari Ini</p>
                                <h2 class="text-xl sm:text-3xl font-bold mt-1">{{ $totalKunjunganHariIni }}</h2>
                                <p class="text-[10px] sm:text-xs text-sky-100 mt-1">{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</p>
                            </div>
                            <div class="w-8 h-8 sm:w-11 sm:h-11 bg-white/15 rounded-xl flex items-center justify-center text-base sm:text-xl shrink-0">🚪</div>
                        </div>
                    </div>

                </div>
            </div>
            {{-- AKSES CEPAT --}}
                        <div>
                <h3 class="text-xs sm:text-sm font-semibold text-gray-500 mb-2 sm:mb-3">Akses Cepat</h3>
                <div class="grid grid-cols-3 lg:grid-cols-6 gap-2 sm:gap-3">
                    <a href="{{ route('pengurus.buku.create') }}" class="bg-white rounded-xl border border-gray-100 p-2 sm:p-4 text-center hover:shadow-sm hover:border-sky-200 transition">
                        <div class="text-lg sm:text-2xl mb-1">➕</div>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Tambah Buku</p>
                    </a>
                    <a href="{{ route('pengurus.anggota.create') }}" class="bg-white rounded-xl border border-gray-100 p-2 sm:p-4 text-center hover:shadow-sm hover:border-sky-200 transition">
                        <div class="text-lg sm:text-2xl mb-1">🧑‍🎓</div>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Tambah Anggota</p>
                    </a>
                    <a href="{{ route('pengurus.peminjaman.offline.create') }}" class="bg-white rounded-xl border border-gray-100 p-2 sm:p-4 text-center hover:shadow-sm hover:border-sky-200 transition">
                        <div class="text-lg sm:text-2xl mb-1">🤝</div>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Pinjam Offline</p>
                    </a>
                    <a href="{{ route('pengurus.peminjaman-paket.create') }}" class="bg-white rounded-xl border border-gray-100 p-2 sm:p-4 text-center hover:shadow-sm hover:border-sky-200 transition">
                        <div class="text-lg sm:text-2xl mb-1">📦</div>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Pinjam Paket</p>
                    </a>
                    <a href="{{ route('pengurus.laporan.index') }}" class="bg-white rounded-xl border border-gray-100 p-2 sm:p-4 text-center hover:shadow-sm hover:border-sky-200 transition">
                        <div class="text-lg sm:text-2xl mb-1">📊</div>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Buat Laporan</p>
                    </a>
                    <a href="{{ route('pengurus.kategori.index') }}" class="bg-white rounded-xl border border-gray-100 p-2 sm:p-4 text-center hover:shadow-sm hover:border-sky-200 transition">
                        <div class="text-lg sm:text-2xl mb-1">🏷️</div>
                        <p class="text-[10px] sm:text-xs font-medium text-gray-600 leading-tight">Kategori</p>
                    </a>
                </div>
            </div>
            {{-- DAFTAR KUNJUNGAN HARI INI --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3 sm:p-6">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <h3 class="text-xs sm:text-base font-semibold text-gray-800">🚪 Kunjungan Hari Ini</h3>
                    <span class="text-[10px] sm:text-xs text-gray-400">{{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}</span>
                </div>

                @if($kunjunganHariIni->isEmpty())
                    <p class="text-gray-400 text-xs sm:text-sm text-center py-6 sm:py-8">Belum ada kunjungan tercatat hari ini.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs sm:text-sm text-left">
                            <thead>
                                <tr class="border-b border-gray-100 text-gray-500">
                                    <th class="py-2 pr-3 sm:pr-4 font-medium whitespace-nowrap">Jam</th>
                                    <th class="py-2 pr-3 sm:pr-4 font-medium whitespace-nowrap">Nama</th>
                                    <th class="py-2 pr-3 sm:pr-4 font-medium whitespace-nowrap">Kelas/Instansi</th>
                                    <th class="py-2 pr-3 sm:pr-4 font-medium whitespace-nowrap">Jenis</th>
                                    <th class="py-2 pr-3 sm:pr-4 font-medium whitespace-nowrap">Keperluan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kunjunganHariIni as $item)
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-2 sm:py-2.5 pr-3 sm:pr-4 text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') }}</td>
                                    <td class="py-2 sm:py-2.5 pr-3 sm:pr-4 font-medium text-gray-800">{{ $item->nama_pengunjung }}</td>
                                    <td class="py-2 sm:py-2.5 pr-3 sm:pr-4 text-gray-500 whitespace-nowrap">{{ $item->kelas_pengunjung ?? '-' }}</td>
                                    <td class="py-2 sm:py-2.5 pr-3 sm:pr-4">
                                        <span class="text-[10px] sm:text-xs font-semibold px-1.5 sm:px-2 py-0.5 rounded-full whitespace-nowrap
                                            {{ $item->jenis_pengunjung == 'anggota' ? 'bg-sky-100 text-sky-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($item->jenis_pengunjung) }}
                                        </span>
                                    </td>
                                    <td class="py-2 sm:py-2.5 pr-3 sm:pr-4 text-gray-500">{{ $item->tujuan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($totalKunjunganHariIni > 10)
                        <p class="text-[10px] sm:text-xs text-gray-400 mt-3 text-center">
                            Menampilkan 10 dari {{ $totalKunjunganHariIni }} kunjungan hari ini.
                        </p>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
        <footer class="bg-white border-t border-gray-100 mt-4 py-4 sm:py-6 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-1.5 sm:gap-2 text-[10px] sm:text-xs text-gray-400 text-center">
    <p>© {{ date('Y') }} Perpustakaan SMK Negeri 1 Bojongpicung. Sistem Perpustakaan Digital.</p>
            <p>Login sebagai <span class="font-medium text-gray-500">{{ auth()->user()->name }}</span> (Pengurus)</p>
        </div>
    </footer>

</x-app-layout>