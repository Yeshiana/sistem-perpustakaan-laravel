<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">Detail Buku</h2>
        </div>
    </x-slot>

    <div class="py-3 sm:py-8">
        <div class="max-w-4xl mx-auto px-3 sm:px-6 lg:px-8 space-y-4 sm:space-y-5">

            {{-- KARTU UTAMA --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-sky-700 to-blue-600 h-16 sm:h-20"></div>
                <div class="px-4 sm:px-8 pb-5 sm:pb-6">
                    <div class="flex flex-col items-center text-center gap-3 sm:flex-row sm:items-end sm:text-left sm:gap-6 -mt-10 mb-5 sm:mb-6">
                        {{-- Cover --}}
                        @if($buku->cover)
                            <img src="{{ asset('storage/'.$buku->cover) }}"
                                 class="w-24 h-32 object-cover rounded-xl border-4 border-white shadow-lg shrink-0">
                        @else
                            <div class="w-24 h-32 bg-sky-100 rounded-xl border-4 border-white shadow-lg flex items-center justify-center shrink-0">
                                <svg class="w-10 h-10 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.966 8.966 0 0 0-6 2.292m0-14.25v14.25"/>
                                </svg>
                            </div>
                        @endif
                        <div class="pb-1">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 leading-tight">{{ $buku->judul }}</h3>
                            <p class="text-gray-500 text-sm mt-0.5">{{ $buku->pengarang }}</p>
                            <div class="flex flex-wrap justify-center sm:justify-start items-center gap-2 mt-2">
                                <span class="bg-sky-50 text-sky-600 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $buku->kategori?->nama ?? '-' }}
                                </span>
                                <span class="text-xs font-semibold px-3 py-1 rounded-full
                                    {{ $buku->stok == 0 ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }}">
                                    Stok: {{ $buku->stok }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- INFO DETAIL --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-sm">
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400 font-medium">ISBN</p>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $buku->isbn ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Penerbit</p>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $buku->penerbit ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Tahun Terbit</p>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $buku->tahun_terbit ?? '-' }}</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Lokasi Rak</p>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $buku->rak ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Kode Buku</p>
                                <p class="text-gray-800 font-medium mt-0.5 font-mono text-xs">{{ $buku->kode_buku ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-medium">Ditambahkan</p>
                                <p class="text-gray-800 font-medium mt-0.5">{{ $buku->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SINOPSIS --}}
            @if($buku->sinopsis)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-3">Sinopsis</h4>
                <p class="text-gray-700 text-sm leading-relaxed">{{ $buku->sinopsis }}</p>
            </div>
            @endif

            {{-- TOMBOL --}}
            <div class="flex gap-2 sm:justify-between">
                <a href="{{ route('pengurus.buku.index') }}"
                   class="flex-1 sm:flex-none text-center border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                    ← Kembali
                </a>
                <a href="{{ route('pengurus.buku.edit', $buku) }}"
                   class="flex-1 sm:flex-none text-center bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-medium transition shadow-sm">
                    Edit Buku
                </a>
            </div>

        </div>
    </div>
</x-app-layout>