<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            Notifikasi
        </h2>
    </x-slot>

    @php
        $kategoriAktif = request('kategori', '');

        $tabs = [
            ''             => '📬 Semua',
            'peminjaman'   => '📋 Peminjaman',
            'pengembalian' => '📦 Pengembalian',
            'sanksi'       => '⚠️ Sanksi',
        ];

        $ikon = [
            'peminjaman'   => ['emoji' => '📋', 'bg' => 'bg-sky-100'],
            'pengembalian' => ['emoji' => '📦', 'bg' => 'bg-emerald-100'],
            'sanksi'       => ['emoji' => '⚠️', 'bg' => 'bg-amber-100'],
        ];
    @endphp

    <div class="py-4 sm:py-8">
        <div class="max-w-3xl mx-auto px-3 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 text-sm px-4 py-3 rounded-xl mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-xl p-4 sm:p-6">

                {{-- Judul + Hapus Semua --}}
                <div class="flex justify-between items-center gap-3 mb-3">
                    <h3 class="font-bold text-gray-800">Daftar Notifikasi</h3>
                    @if($notifikasi->count() > 0)
                        <form action="{{ route('pengurus.notifikasi.destroyAll') }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Hapus semua notifikasi?')"
                                class="text-sm text-red-500 hover:bg-red-50 active:bg-red-100 px-3 h-9 rounded-lg transition">
                                Hapus Semua
                            </button>
                        </form>
                    @endif
                </div>

                {{-- SEARCH + FILTER KATEGORI --}}
                <div class="flex flex-col sm:flex-row gap-2 mb-4">
                    <form id="formCari" class="flex flex-1 gap-2">
                        <input type="search" id="cariNotif" placeholder="Cari notifikasi atau nama..." autocomplete="off"
                               class="flex-1 min-w-0 h-11 sm:h-10 border border-gray-300 rounded-lg px-3 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                        <button type="submit"
                                class="shrink-0 h-11 sm:h-10 px-4 inline-flex items-center justify-center gap-1.5 bg-sky-600 hover:bg-sky-700 active:scale-[.98] text-white rounded-lg text-sm font-medium transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                            Cari
                        </button>
                    </form>
                    <form method="GET" action="{{ route('pengurus.notifikasi') }}">
                        <select name="kategori" onchange="this.form.submit()"
                                class="w-full sm:w-auto h-11 sm:h-10 border border-gray-300 rounded-lg px-3 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                            @foreach($tabs as $nilai => $label)
                                <option value="{{ $nilai }}" {{ $kategoriAktif === $nilai ? 'selected' : '' }}>
                                    {{ $nilai === '' ? '📬 Semua Notifikasi' : $label }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                {{-- DAFTAR NOTIFIKASI --}}
                <div class="space-y-2">
                    @forelse($notifikasi as $item)
                        @php $i = $ikon[$item->kategori] ?? ['emoji' => '🔔', 'bg' => 'bg-gray-100']; @endphp

                        <div data-notif="{{ \Illuminate\Support\Str::lower($item->pesan) }}"
                             class="notif-item flex items-start gap-3 p-3 sm:p-4 rounded-xl
                                    {{ $item->is_read ? 'bg-gray-50' : 'bg-blue-50 border border-blue-100' }}">

                            {{-- Ikon ukuran tetap supaya teks selalu sejajar --}}
                            <div class="relative shrink-0 w-10 h-10 rounded-xl flex items-center justify-center text-xl {{ $i['bg'] }}">
                                {{ $i['emoji'] }}
                                @unless($item->is_read)
                                    <span class="absolute -top-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-sky-500 ring-2 ring-white"></span>
                                @endunless
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-gray-700 leading-relaxed break-words {{ $item->is_read ? '' : 'font-medium' }}">
                                    {{ $item->pesan }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1.5 flex flex-wrap items-center gap-x-1.5 gap-y-0.5">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z"/></svg>
                                        {{ $item->created_at->locale('id')->translatedFormat('d M Y, H:i') }} WIB
                                    </span>
                                    <span class="text-gray-400">({{ $item->created_at->locale('id')->diffForHumans() }})</span>
                                </p>
                            </div>

                            <form action="{{ route('pengurus.notifikasi.destroy', $item) }}" method="POST" class="shrink-0 -mr-1 -mt-1">
                                @csrf @method('DELETE')
                                <button type="submit" aria-label="Hapus notifikasi"
                                        class="w-10 h-10 flex items-center justify-center rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 active:bg-red-100 transition">
                                    ✕
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-5xl mb-3">🔔</div>
                            <p class="text-gray-400 text-sm">Belum ada notifikasi</p>
                        </div>
                    @endforelse
                </div>

                {{-- Muncul kalau hasil pencarian kosong --}}
                <div id="cariKosong" class="hidden text-center py-10">
                    <div class="text-4xl mb-2">🔍</div>
                    <p class="text-gray-400 text-sm">Tidak ada notifikasi yang cocok dengan pencarian.</p>
                </div>

            </div>
        </div>
    </div>

    <script>
        (function () {
            const form   = document.getElementById('formCari');
            const input  = document.getElementById('cariNotif');
            const items  = document.querySelectorAll('.notif-item');
            const kosong = document.getElementById('cariKosong');
            if (!form || !input) return;

            function cari() {
                const kata = input.value.trim().toLowerCase();
                let tampil = 0;

                items.forEach(function (el) {
                    const cocok = el.dataset.notif.includes(kata);
                    el.style.display = cocok ? '' : 'none';
                    if (cocok) tampil++;
                });

                kosong.classList.toggle('hidden', items.length === 0 || tampil > 0);
            }

            // Tombol "Cari" atau tombol Enter di keyboard HP
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                cari();
                input.blur();
            });

            // Tetap menyaring langsung saat mengetik; juga saat tombol × bawaan field diklik
            input.addEventListener('input', cari);
        })();
    </script>
</x-app-layout>