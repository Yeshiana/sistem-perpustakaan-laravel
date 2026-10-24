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
        Dashboard
    </h2>
</x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- HERO SECTION --}}
@if(!empty($pengingat))
<div class="mb-6 rounded-2xl overflow-hidden shadow {{ $adaTerlambat ? 'bg-red-50 border border-red-200' : 'bg-yellow-50 border border-yellow-200' }} py-3">
    <div class="whitespace-nowrap overflow-hidden">
        <div class="inline-block animate-marquee">
            @foreach(array_merge($pengingat, $pengingat) as $item)
                <span class="text-sm font-semibold {{ $item['terlambat'] ? 'text-red-700' : 'text-yellow-700' }} px-8">
                    ⏰ {{ $item['teks'] }}
                </span>
            @endforeach
        </div>
    </div>
</div>

<style>
@keyframes marquee {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.animate-marquee {
    animation: marquee 20s linear infinite;
    padding-left: 100%;
}
.animate-marquee:hover {
    animation-play-state: paused;
}
</style>
@endif

            {{-- HERO SECTION --}}
<div
    class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 shadow-xl">
    <div class="absolute inset-0 opacity-10">
        <div
            class="absolute -top-24 -left-24 w-72 h-72 bg-white rounded-full blur-3xl">
        </div>
    </div>

    <div class="relative grid lg:grid-cols-2 items-center">

                {{-- Kiri --}}
        <div class="p-5 sm:p-10">

                        <span
                class="inline-flex items-center px-3 py-1 rounded-full bg-white/20 text-white text-xs sm:text-sm">

                📚 Dashboard Anggota
            </span>

                        <h1 class="text-2xl sm:text-4xl font-bold text-white mt-3 sm:mt-5 leading-tight">
                Selamat Datang,
                <br>
                {{ auth()->user()->name }} 👋
            </h1>

            <p class="mt-3 sm:mt-4 text-sm sm:text-base text-blue-100 leading-6 sm:leading-7 max-w-lg">
                Jelajahi ribuan koleksi buku perpustakaan sekolah.

                Lakukan peminjaman, pantau status buku, serta lihat riwayat
                peminjamanmu dengan lebih mudah.
            </p>

                        <div class="mt-5 sm:mt-8 max-w-lg">
    <form action="{{ route('siswa.katalog') }}" method="GET">
        <div class="flex overflow-hidden rounded-2xl bg-white shadow-lg">

            <div class="flex items-center px-3 sm:px-4">
                🔍
            </div>

            <input
                type="text"
                name="search"
                placeholder="Cari buku..."
                class="flex-1 border-0 focus:ring-0 py-2.5 sm:py-4 text-sm sm:text-base text-gray-700 min-w-0">

            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-8 text-sm sm:text-base font-semibold">

                Cari

            </button>

        </div>
    </form>
</div>

        </div>
               {{-- Gambar versi HP (di bawah teks, tanpa gradasi) --}}
        <div class="block lg:hidden px-5 sm:px-10 pb-5 sm:pb-10 -mt-2">
            <img src="{{ asset('images/dashboard-reading.png') }}"
                 class="w-full h-32 sm:h-48 object-cover object-top rounded-2xl">
        </div>

        {{-- Kanan (desktop) --}}
        <div class="hidden lg:block relative self-stretch">
            {{-- Gradasi kiri supaya nyambung --}}
            <div class="absolute inset-y-0 left-0 w-40 z-10
                        bg-gradient-to-r from-indigo-600 to-transparent">
            </div>
            <img src="{{ asset('images/dashboard-reading.png') }}"
                 class="w-full h-full object-cover object-top">
        </div>
</div>

</div>

<div class="mt-6 sm:mt-10"></div>
{{-- ATURAN PEMINJAMAN --}}
<div class="mt-4 sm:mt-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
    <h3 class="text-sm sm:text-base font-bold text-gray-800 mb-2 sm:mb-4">📋 Aturan Peminjaman</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-4">
        <div class="flex items-start gap-3 bg-blue-50 rounded-xl p-3 sm:p-4">
            <div class="w-9 h-9 bg-blue-100 rounded-lg flex items-center justify-center text-lg shrink-0">📚</div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Maksimal 3 Buku</p>
                <p class="text-xs text-gray-500 mt-0.5">Peminjaman aktif per anggota maksimal 3 buku sekaligus.</p>
            </div>
        </div>
        <div class="flex items-start gap-3 bg-indigo-50 rounded-xl p-3 sm:p-4">
            <div class="w-9 h-9 bg-indigo-100 rounded-lg flex items-center justify-center text-lg shrink-0">⏳</div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Maksimal 3 Hari Kerja</p>
                <p class="text-xs text-gray-500 mt-0.5">Masa peminjaman buku maksimal 3 hari kerja (Sabtu &amp; Minggu tidak dihitung).</p>
            </div>
        </div>
        <div class="flex items-start gap-3 bg-sky-50 rounded-xl p-3 sm:p-4">
            <div class="w-9 h-9 bg-sky-100 rounded-lg flex items-center justify-center text-lg shrink-0">🕑</div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Booking s.d. Jam 14.00</p>
                <p class="text-xs text-gray-500 mt-0.5">Peminjaman buku secara online hanya dilayani sampai pukul 14.00 WIB.</p>
            </div>
        </div>
        <div class="flex items-start gap-3 bg-amber-50 rounded-xl p-3 sm:p-4">
            <div class="w-9 h-9 bg-amber-100 rounded-lg flex items-center justify-center text-lg shrink-0">🔒</div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Kembalikan Dulu Sebelum Pinjam Lagi</p>
                <p class="text-xs text-gray-500 mt-0.5">Jika salah satu dari 3 buku belum dikembalikan, kamu tidak dapat mengajukan peminjaman baru sampai buku tersebut dikembalikan.</p>
            </div>
        </div>
        <div class="flex items-start gap-3 bg-red-50 rounded-xl p-3 sm:p-4">
            <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center text-lg shrink-0">⚠️</div>
            <div>
                <p class="text-sm font-semibold text-gray-800">Sanksi Pelanggaran</p>
                <p class="text-xs text-gray-500 mt-0.5">Terlambat/buku rusak dikenakan sanksi. Tidak mengambil buku yang sudah disetujui berakibat larangan pinjam selama 2 minggu. Sanksi harus diselesaikan sebelum bisa meminjam lagi.</p>
            </div>
        </div>
    </div>
</div>
<div class="mt-6"></div>

{{-- BATAS PERUBAHAN NOTIF --}}


<div class="grid grid-cols-2 md:grid-cols-2 xl:grid-cols-5 gap-3 sm:gap-6">

    <div class="bg-white rounded-2xl shadow p-3 sm:p-6">

        <div class="flex justify-between">

            <div>

                <p class="text-gray-500 text-xs sm:text-sm">
                    Sedang Dipinjam
                </p>

                <h2 class="text-2xl sm:text-4xl font-bold mt-1 sm:mt-2">
                    {{ $dipinjam ?? 0 }}
                </h2>

                <p class="text-xs sm:text-sm text-gray-400">
                    Buku
                </p>

            </div>

            <div class="text-3xl sm:text-5xl">
                📘
            </div>

        </div>

    </div>

        <div class="bg-white rounded-2xl shadow p-3 sm:p-6">

        <div class="flex justify-between">

            <div>

                <p class="text-gray-500 text-xs sm:text-sm">
                    Total Riwayat
                </p>

                <h2 class="text-2xl sm:text-4xl font-bold mt-1 sm:mt-2">
                    {{ $riwayat ?? 0 }}
                </h2>

            </div>

            <div class="text-3xl sm:text-5xl">
                📚
            </div>

        </div>

    </div>

        <div class="bg-white rounded-2xl shadow p-3 sm:p-6">

        <div class="flex justify-between">

            <div>

                <p class="text-gray-500 text-xs sm:text-sm">
                    Jatuh Tempo
                </p>

                <h2 class="text-2xl sm:text-4xl font-bold mt-1 sm:mt-2">

                    {{ $jatuhTempo ?? 0 }}

                </h2>

            </div>

            <div class="text-3xl sm:text-5xl">
                ⏰
            </div>

        </div>

    </div>

        <div class="bg-white rounded-2xl shadow p-3 sm:p-6">

        <div class="flex justify-between">

            <div>

                <p class="text-gray-500 text-xs sm:text-sm">
                    Menunggu
                </p>

                <h2 class="text-2xl sm:text-4xl font-bold mt-1 sm:mt-2">

                    {{ $menunggu ?? 0 }}

                </h2>

            </div>

            <div class="text-3xl sm:text-5xl">
                📄
            </div>

        </div>

    </div>

        <div class="bg-white rounded-2xl shadow p-3 sm:p-6">

        <div class="flex justify-between">

            <div>

                <p class="text-gray-500 text-xs sm:text-sm">
                    Total Buku
                </p>

                <h2 class="text-2xl sm:text-4xl font-bold">

                    {{ $buku->count() }}

                </h2>

            </div>

            <div class="text-3xl sm:text-5xl">
                📖
            </div>

        </div>

    </div>

</div>


            {{-- Katalog Buku --}}
            <div class="bg-white rounded-xl shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-bold text-gray-800">📚 Katalog Buku Tersedia</h4>
                    <a href="{{ route('siswa.katalog') }}"
                       class="text-indigo-600 text-sm hover:underline">Lihat semua →</a>
                </div>

                @if($buku->isEmpty())
                    <p class="text-gray-400 text-sm">Belum ada buku tersedia.</p>
                @else
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                {{-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">    --}}
                    
                    
                {{-- @foreach($buku as $item)
                        <div class="border rounded-lg overflow-hidden hover:shadow-md transition">
                            @if($item->cover)
                                <img src="{{ asset('storage/'.$item->cover) }}"
                                     alt="{{ $item->judul }}"
                                     class="w-full h-36 object-cover">
                            @else
                                <div class="w-full h-36 bg-indigo-50 flex items-center justify-center text-4xl">
                                    📖
                                </div>
                            @endif
                            <div class="p-2">
                                <p class="text-xs font-semibold text-gray-800 line-clamp-2">{{ $item->judul }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $item->pengarang }}</p>
                                <span class="text-xs {{ $item->stok > 0 ? 'text-green-600' : 'text-red-500' }}">
                                    Stok: {{ $item->stok }}
                                </span>
                            </div>
                        </div>
                        @endforeach --}}


                        @foreach($buku as $item)

<div class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow hover:shadow-2xl hover:-translate-y-2 transition duration-300 flex flex-col h-full">

                        {{-- Cover --}}
    <div class="relative">

        @if($item->cover)

            <img src="{{ asset('storage/'.$item->cover) }}"
                class="w-full h-56 object-cover">

        @else

            <div class="w-full h-56 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-6xl">

                📚

            </div>

        @endif

        <span class="absolute top-3 left-3 bg-blue-600 text-white text-xs px-3 py-1 rounded-full shadow">
    {{ $item->kategori->nama ?? 'Buku' }}
</span>

    </div>

    {{-- Isi --}}
        <div class="p-5 flex flex-col flex-1">

                <div class="flex-1">
            <h3 class="font-bold text-gray-800 line-clamp-2">

                {{ $item->judul }}

            </h3>

            <p class="text-sm text-gray-500 mt-1">

                {{ $item->pengarang }}

            </p>
        </div>

        <div class="flex items-center justify-between mt-4">
            @if($item->stok>0)

                <span class="text-green-600 font-semibold text-sm">

                    🟢 Tersedia

                </span>

            @else

                <span class="text-red-500 font-semibold text-sm">

                    🔴 Habis

                </span>

            @endif

            <span class="text-xs text-gray-400">

                {{ $item->stok }} Buku

            </span>

        </div>

        <button type="button" onclick="openModalDashboard(this)"
            data-cover="{{ $item->cover ? asset('storage/'.$item->cover) : asset('images/no-cover.png') }}"
            data-judul="{{ $item->judul }}"
            data-kategori="{{ $item->kategori->nama ?? 'Umum' }}"
            data-pengarang="{{ $item->pengarang }}"
            data-penerbit="{{ $item->penerbit }}"
            data-tahun="{{ $item->tahun_terbit }}"
            data-isbn="{{ $item->isbn }}"
            data-rak="{{ $item->rak }}"
            data-stok="{{ $item->stok_tersedia }}"
            data-sinopsis="{{ $item->sinopsis }}"
            class="mt-auto pt-4 flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white rounded-lg py-2 text-sm font-semibold transition">
            Detail Buku

        </button>

    </div>

</div>

            @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

{{-- FOOTER --}}
<footer class="mt-16 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-14">

               {{-- Judul --}}
        <div class="text-center">

    <h2 class="text-xl sm:text-3xl font-bold">
        📚 Perpustakaan Digital
    </h2>

    <p class="mt-3 sm:mt-6 italic text-sm sm:text-xl text-blue-100 max-w-3xl mx-auto leading-6 sm:leading-9">

        "Membaca adalah jendela dunia.
        Setiap halaman yang dibuka adalah langkah menuju pengetahuan
        dan masa depan yang lebih baik."

    </p>

</div>

        {{-- Tentang Sekolah --}}
        <div class="text-center max-w-3xl mx-auto mt-6 sm:mt-10">
            <h3 class="font-bold text-sm sm:text-lg mb-2 sm:mb-3">
                🏫 Tentang Sekolah
            </h3>
         <p class="text-xs sm:text-base text-slate-300 leading-relaxed text-justify">
                Pendirian SMK Negeri 1 Bojongpicung di sahkan pada tanggal 15 September 2006
                dengan Surat Keputusan dari Bupati Cianjur Nomor 421.5/Kep.179-Ks/2006 tentang
                Pendirian Sekolah Menengah Kejuruan (SMK). Beralamat di Jl. Darmaga Desa Sukaratu
                Kecamatan Bojongpicung Kabupaten Cianjur 43283 Telp. (0263) 326633.
            </p>
        </div>

        {{-- Garis --}}
        <div class="border-t border-white/10 my-6 sm:my-10"></div>

        {{-- Informasi --}}
                <div class="grid grid-cols-2 gap-4 sm:gap-10 max-w-2xl mx-auto text-center">
            {{-- Layanan --}}
            <div>

                <h3 class="font-bold text-sm sm:text-lg mb-2 sm:mb-4">
                    📖 Layanan
                </h3>

                <ul class="space-y-1 sm:space-y-2 text-xs sm:text-base text-slate-300">

                    <li>✔️ Katalog Buku</li>

                    <li>✔️ Peminjaman Buku</li>

                    <li>✔️ Riwayat Peminjaman</li>

                    <li>✔️ Notifikasi</li>

                </ul>

            </div>

            {{-- Operasional --}}
            <div>

                <h3 class="font-bold text-sm sm:text-lg mb-2 sm:mb-4">
                    ⏰ Jam Operasional
                </h3>

                <ul class="space-y-1 sm:space-y-2 text-xs sm:text-base text-slate-300">

                    <li>Senin - Jumat</li>

                    <li>07.00 - 15.00 WIB</li>

                    <li>Sabtu & Minggu Libur</li>

                </ul>

            </div>

        </div>
        
        {{-- Copyright --}}
    <div class="border-t border-white/10">

        <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 flex flex-col md:flex-row justify-between items-center gap-1 md:gap-0 text-center text-xs sm:text-sm text-slate-400">

            <p>
                © {{ date('Y') }} Sistem Informasi Perpustakaan SMKN 1 Bojongpicung
            </p>

            <p class="text-slate-500">
                Developed by <span class="text-slate-300 font-medium">@Yeshiana</span>
            </p>

        </div>

    </div>

</footer>

{{-- MODAL DETAIL BUKU --}}
<div id="detailModalDashboard"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] overflow-y-auto">

        <div class="flex items-center justify-between border-b px-6 py-4">
            <h2 class="text-xl font-bold text-gray-800">Detail Buku</h2>
            <button onclick="closeModalDashboard()" class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
        </div>

        <div class="p-6">
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <img id="modalCoverD" src="" class="w-full h-56 md:h-auto object-contain bg-gray-50 rounded-xl shadow">
                </div>

                <div class="md:col-span-2">
                    <span id="modalKategoriD" class="inline-block bg-sky-100 text-sky-700 text-xs px-3 py-1 rounded-full"></span>
                    <h2 id="modalJudulD" class="text-2xl font-bold mt-3 text-gray-800"></h2>

                    <div class="mt-5 space-y-2 text-sm text-gray-600">
                        <p>👤 <b>Pengarang :</b> <span id="modalPengarangD"></span></p>
                        <p>🏢 <b>Penerbit :</b> <span id="modalPenerbitD"></span></p>
                        <p>📅 <b>Tahun :</b> <span id="modalTahunD"></span></p>
                        <p>📖 <b>ISBN :</b> <span id="modalISBND"></span></p>
                        <p>🗄️ <b>Rak :</b> <span id="modalRakD"></span></p>
                        <p>📦 <b>Stok :</b> <span id="modalStokD"></span> Buku</p>
                    </div>

                    <a href="{{ route('siswa.katalog') }}" id="modalPinjamLinkD"
                       class="inline-block mt-5 bg-sky-600 hover:bg-sky-700 text-white rounded-xl px-5 py-2 text-sm font-semibold transition">
                        Pinjam Buku Ini →
                    </a>
                </div>
            </div>

            <hr class="my-6">
            <h3 class="font-semibold text-gray-800 mb-2">Sinopsis</h3>
            <p id="modalSinopsisD" class="text-gray-600 leading-7 text-sm"></p>
        </div>
    </div>
</div>

<script>
function openModalDashboard(button) {
    document.getElementById('modalCoverD').src = button.dataset.cover;
    document.getElementById('modalKategoriD').innerHTML = button.dataset.kategori;
    document.getElementById('modalJudulD').innerHTML = button.dataset.judul;
    document.getElementById('modalPengarangD').innerHTML = button.dataset.pengarang;
    document.getElementById('modalPenerbitD').innerHTML = button.dataset.penerbit;
    document.getElementById('modalTahunD').innerHTML = button.dataset.tahun;
    document.getElementById('modalISBND').innerHTML = button.dataset.isbn;
    document.getElementById('modalRakD').innerHTML = button.dataset.rak;
    document.getElementById('modalStokD').innerHTML = button.dataset.stok;
    document.getElementById('modalSinopsisD').innerHTML = button.dataset.sinopsis;

    // Arahkan tombol "Pinjam Buku Ini" ke katalog dengan judul buku ini dicari otomatis
    document.getElementById('modalPinjamLinkD').href =
        "{{ route('siswa.katalog') }}?search=" + encodeURIComponent(button.dataset.judul);

    const modal = document.getElementById('detailModalDashboard');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModalDashboard() {
    const modal = document.getElementById('detailModalDashboard');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>
</x-app-layout>

        