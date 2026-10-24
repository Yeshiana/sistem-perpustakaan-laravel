<x-app-layout>
        <x-slot name="header">
        <div class="hidden sm:flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 leading-tight">Katalog Buku</h2>
                <p class="text-sm text-gray-500 mt-0.5">Temukan dan pinjam buku favoritmu</p>
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl">
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            {{-- SEARCH BAR --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <form method="GET" class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari judul atau pengarang..."
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none transition">
                    </div>
                    <button type="submit"
                            class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                        Cari
                    </button>
                    <a href="{{ route('siswa.katalog') }}"
                       class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-6 py-2.5 rounded-xl text-sm font-medium transition text-center">
                        Reset
                    </a>
                </form>
            </div>

            {{-- GRID BUKU --}}
            <div>
                <h3 class="text-base font-semibold text-gray-700 mb-4">
                    @if(request('search'))
                        Hasil pencarian untuk "<span class="text-sky-600">{{ request('search') }}</span>"
                    @else
                        Koleksi Buku Tersedia
                    @endif
                </h3>

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @forelse($buku as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 flex flex-col">
                        {{-- Cover --}}
                        <div class="relative">
                            @if($item->cover)
                                <img src="{{ asset('storage/'.$item->cover) }}"
     class="w-full h-56 object-cover">
                            @else
                                <div class="w-full h-44 bg-gradient-to-br from-sky-100 to-sky-200 flex flex-col items-center justify-center gap-2">
                                    <svg class="w-10 h-10 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.966 8.966 0 0 0-6 2.292m0-14.25v14.25"/>
                                    </svg>
                                    <span class="text-xs text-sky-400 font-medium">No Cover</span>
                                </div>
                            @endif
                            {{-- Badge stok --}}
                            {{-- Badge stok --}}
                            <div class="absolute top-2 right-2">
                                @if($item->stok_tersedia == 0)
                                    <span class="bg-red-500 text-white text-xs font-medium px-2 py-0.5 rounded-full">Habis</span>
                                @elseif($item->stok_tersedia <= 3)
                                    <span class="bg-yellow-500 text-white text-xs font-medium px-2 py-0.5 rounded-full">Sisa {{ $item->stok_tersedia }}</span>
                                @else
                                    <span class="bg-green-500 text-white text-xs font-medium px-2 py-0.5 rounded-full">Tersedia</span>
                                @endif
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="p-3 flex flex-col flex-1">
                            <h4 class="font-bold text-base text-gray-800 leading-snug line-clamp-2">{{ $item->judul }}</h4>
                            <p class="flex items-center gap-1 text-sm text-gray-600 mt-2">
    👤 {{ $item->pengarang }}
</p>

<p class="flex items-center gap-1 text-sm text-gray-600 mt-1">
    📦 Stok : {{ $item->stok_tersedia }} Buku
</p>
 <div class="mb-2">
    {{-- <span class="inline-flex items-center bg-sky-100 text-sky-700
                 text-[11px] font-semibold px-3 py-1 rounded-full">
        {{ $item->kategori ?? 'Umum' }}
    </span> --}}

    <span class="inline-flex items-center bg-sky-100 text-sky-700
             text-[11px] font-semibold px-3 py-1 rounded-full">
    {{ $item->kategori->nama ?? 'Umum' }}
</span>
</div>
                            <div class="mt-auto">
                                <div class="mt-auto">

    @if($item->stok_tersedia > 0)

        <div class="grid grid-cols-2 gap-2">

    <button type="button" onclick="openModal(this)"

    data-cover="{{ $item->cover ? asset('storage/'.$item->cover) : asset('images/no-cover.png') }}"
    data-judul="{{ $item->judul }}"
    {{-- data-kategori="{{ $item->kategori }}" --}}
    data-kategori="{{ $item->kategori->nama ?? 'Umum' }}"
    data-pengarang="{{ $item->pengarang }}"
    data-penerbit="{{ $item->penerbit }}"
    data-tahun="{{ $item->tahun_terbit }}"
    data-isbn="{{ $item->isbn }}"
    data-rak="{{ $item->rak }}"
    data-stok="{{ $item->stok_tersedia }}"
    data-sinopsis="{{ $item->sinopsis }}"

    class="border border-sky-500 text-sky-600 rounded-lg py-2 text-sm font-medium hover:bg-sky-50 transition">

    Detail

</button>
            {{-- <button
                type="button"
                class="bg-sky-600 text-white
                       rounded-lg py-2 text-sm font-semibold
                       hover:bg-sky-700 transition">

                Pinjam

            </button> --}}

            <button
    type="button"
    onclick="openPinjamModal({{ $item->id }}, '{{ addslashes($item->judul) }}')"
    class="bg-sky-600 text-white
           rounded-lg py-2 text-sm font-semibold
           hover:bg-sky-700 transition">

    Pinjam

</button>


        </div>

    @else

        <button
            disabled
            class="w-full bg-gray-100 text-gray-400 py-2 rounded-lg">

            Stok Habis

        </button>

    @endif

</div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-5 py-16 text-center">
                        <svg class="w-14 h-14 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.966 8.966 0 0 0-6 2.292m0-14.25v14.25"/>
                        </svg>
                        <p class="text-gray-400 text-sm font-medium">Buku tidak ditemukan</p>
                        <p class="text-gray-300 text-xs mt-1">Coba kata kunci lain</p>
                    </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="mt-6">{{ $buku->links() }}</div>
            </div>
         </div>

         {{-- tombol DETAIL --}}
    <div id="detailModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-3xl mx-4 max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between border-b px-6 py-4 shrink-0">
            <h2 class="text-xl font-bold text-gray-800">
                Detail Buku
            </h2>

            <button
                onclick="closeModal()"
                class="text-gray-500 hover:text-red-500 text-2xl">

                &times;

            </button>

        </div>

        {{-- Isi Modal --}}
        <div class="p-6 overflow-y-auto flex-1">

            <div class="grid md:grid-cols-3 gap-6">

    {{-- Cover --}}
    <div>

                <img id="modalCover"
     src=""
     alt="Cover buku"
     onclick="bukaFoto(this.src)"
     class="w-full h-56 md:h-auto object-contain bg-gray-50 rounded-xl shadow cursor-zoom-in">
    </div>

    {{-- Informasi --}}
    <div class="md:col-span-2">

        <span id="modalKategori"
              class="inline-block bg-sky-100 text-sky-700 text-xs px-3 py-1 rounded-full">
        </span>

        <h2 id="modalJudul"
            class="text-2xl font-bold mt-3 text-gray-800">
        </h2>

        <div class="mt-5 space-y-2 text-sm text-gray-600">

            <p>👤 <b>Pengarang :</b> <span id="modalPengarang"></span></p>

            <p>🏢 <b>Penerbit :</b> <span id="modalPenerbit"></span></p>

            <p>📅 <b>Tahun :</b> <span id="modalTahun"></span></p>

            <p>📖 <b>ISBN :</b> <span id="modalISBN"></span></p>

            <p>🗄️ <b>Rak :</b> <span id="modalRak"></span></p>

            <p>📦 <b>Stok :</b> <span id="modalStok"></span> Buku</p>

        </div>

    </div>

</div>

<hr class="my-6">

<h3 class="font-semibold text-gray-800 mb-2">
    Sinopsis
</h3>

<p id="modalSinopsis"
   class="text-gray-600 leading-7 text-sm">
</p>
        </div>

    </div>

</div>


{{-- LIGHTBOX FOTO COVER --}}
<div id="lightboxFoto"
     class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4"
     onclick="tutupFoto()">
    <button type="button" onclick="tutupFoto()"
            class="absolute top-4 right-4 bg-white/90 text-gray-800 rounded-full w-10 h-10 flex items-center justify-center shadow text-lg font-bold">
        ✕
    </button>
    <img id="lightboxImg" src="" alt="Cover buku"
         class="max-w-full max-h-[85vh] object-contain rounded-lg shadow-2xl"
         onclick="event.stopPropagation()">
</div>
{{-- MODAL PINJAM --}}
<div id="pinjamModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">

    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4">

        <div class="flex items-center justify-between border-b px-6 py-4">
            <h2 class="text-xl font-bold text-gray-800">Ajukan Peminjaman</h2>
            <button type="button" onclick="closePinjamModal()"
                    class="text-gray-500 hover:text-red-500 text-2xl">&times;</button>
        </div>

        <form method="POST" action="{{ route('siswa.pinjam') }}" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="buku_id" id="pinjamBukuId">

            <div>
                <p class="text-sm text-gray-500">Buku yang dipinjam</p>
                <p id="pinjamJudul" class="font-semibold text-gray-800"></p>
            </div>

            <div>
                <label for="tgl_ambil" class="block text-sm font-medium text-gray-700 mb-1">
                    Tanggal ambil buku
                </label>
                <input type="text" name="tgl_ambil" id="pinjamTglAmbil" required
                       class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm
                              focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none">
                
                <ul class="text-xs text-gray-400 mt-2 space-y-1 list-disc list-inside">
    <li>Tanggal Pinjam Maksimal 2 hari kerja dari sekarang</li>
    <li>Ambil buku sebelum pukul 15:00 pada tanggal yang dipilih</li>
    <li>Booking setelah pukul 13:59 otomatis dihitung mulai besok</li>
</ul>
            </div>

            <button type="submit"
                    class="w-full bg-sky-600 hover:bg-sky-700 text-white rounded-xl
                           py-2.5 font-semibold transition">
                Ajukan Peminjaman
            </button>
        </form>
    </div>
</div>

            {{-- FOOTER --}}
<div class="relative mt-20">

    {{-- Efek gradasi agar transisi lebih halus --}}
    <div class="absolute -top-20 left-0 w-full h-24
                bg-gradient-to-b from-transparent via-slate-100 to-slate-900">
    </div>

       <footer class="relative bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">

            <div class="text-center">

                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-20 sm:h-20 rounded-full bg-white/10 backdrop-blur mb-3 sm:mb-6">
                    <span class="text-xl sm:text-4xl">📚</span>
                </div>

                <h2 class="text-xl sm:text-3xl font-bold">
                    Perpustakaan Digital
                </h2>

                <p class="mt-3 sm:mt-6 max-w-3xl mx-auto text-blue-100 leading-6 sm:leading-8 italic text-sm sm:text-lg">
                    "Membaca adalah jendela dunia. Setiap halaman yang dibaca membuka
                    wawasan baru, menumbuhkan imajinasi, dan menjadi langkah kecil
                    menuju masa depan yang lebih baik."
                </p>

            </div>

        </div>

        <div class="border-t border-white/10 py-4 sm:py-6">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 flex flex-col md:flex-row items-center justify-between gap-1 md:gap-3 text-center">

                <p class="text-gray-300 text-xs sm:text-sm">
                    © {{ date('Y') }} Sistem Informasi Perpustakaan SMKN 1 Bojongpicung
                </p>

                <p class="text-xs sm:text-sm text-gray-400">
                    Developed by
                    <span class="font-medium text-slate-300">@Yeshiana</span>
                </p>

            </div>
            </div>
    </footer>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/l10n/id.js"></script>



<script>

document.addEventListener('DOMContentLoaded', function () {
    flatpickr.localize(flatpickr.l10ns.id);
});

function openModal(button){

    document.getElementById('modalCover').src =
        button.dataset.cover;

    document.getElementById('modalKategori').innerHTML =
        button.dataset.kategori;

    document.getElementById('modalJudul').innerHTML =
        button.dataset.judul;

    document.getElementById('modalPengarang').innerHTML =
        button.dataset.pengarang;

    document.getElementById('modalPenerbit').innerHTML =
        button.dataset.penerbit;

    document.getElementById('modalTahun').innerHTML =
        button.dataset.tahun;

    document.getElementById('modalISBN').innerHTML =
        button.dataset.isbn;

    document.getElementById('modalRak').innerHTML =
        button.dataset.rak;

    document.getElementById('modalStok').innerHTML =
        button.dataset.stok;

    document.getElementById('modalSinopsis').innerHTML =
        button.dataset.sinopsis;

    const modal = document.getElementById('detailModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

}
function closeModal() {

    const modal = document.getElementById('detailModal');

    modal.classList.remove('flex');
    modal.classList.add('hidden');

}

function bukaFoto(src) {
    if (!src) return;
    document.getElementById('lightboxImg').src = src;
    const box = document.getElementById('lightboxFoto');
    box.classList.remove('hidden');
    box.classList.add('flex');
}

function tutupFoto() {
    const box = document.getElementById('lightboxFoto');
    box.classList.remove('flex');
    box.classList.add('hidden');
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') tutupFoto();
});

// Hitung tanggal setelah sekian hari kerja (skip Sabtu & Minggu)
// Logikanya sama persis dengan tambahHariKerja() di PeminjamanController.php
// Tentukan tanggal dasar booking: kalau sudah jam 14:00 ke atas, mulai dihitung dari besok
function tanggalDasarBooking() {
    let now = new Date();
    let dasar = new Date();
    dasar.setHours(0, 0, 0, 0);
    if (now.getHours() >= 14) {
        dasar.setDate(dasar.getDate() + 1);
    }
    return dasar;
}

function tambahHariKerja(tglDasar, hari) {
    let tgl = new Date(tglDasar);
    let hitungan = 0;
    while (hitungan < hari) {
        tgl.setDate(tgl.getDate() + 1);
        const day = tgl.getDay();
        if (day !== 0 && day !== 6) {
            hitungan++;
        }
    }
    return tgl;
}

let pinjamPicker = null;

function openPinjamModal(id, judul) {
    document.getElementById('pinjamBukuId').value = id;
    document.getElementById('pinjamJudul').innerText = judul;

    const tglDasar = tanggalDasarBooking();
    const tglMax = tambahHariKerja(tglDasar, 2);

    if (!pinjamPicker) {
        pinjamPicker = flatpickr("#pinjamTglAmbil", {
            altInput: true,
            altFormat: "d/m/Y",
            dateFormat: "Y-m-d",
            minDate: tglDasar,
            maxDate: tglMax,
            disable: [
                function (date) {
                    return (date.getDay() === 0 || date.getDay() === 6);
                }
            ]
        });
    } else {
        pinjamPicker.set('minDate', tglDasar);
        pinjamPicker.set('maxDate', tglMax);
    }

    let defaultTgl = new Date(tglDasar);
    while (defaultTgl.getDay() === 0 || defaultTgl.getDay() === 6) {
        defaultTgl.setDate(defaultTgl.getDate() + 1);
    }
    pinjamPicker.setDate(defaultTgl, true);

    const modal = document.getElementById('pinjamModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
function closePinjamModal() {
    const modal = document.getElementById('pinjamModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

</script>
</x-app-layout>