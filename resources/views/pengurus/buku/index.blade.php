<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            Manajemen Koleksi Buku
        </h2>
    </x-slot>
    <div class="py-3 sm:py-12">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 text-sm px-3 py-2 rounded mb-3">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-base sm:text-lg font-semibold">Daftar Buku</h3>
                    <a href="{{ route('pengurus.buku.create') }}"
                       class="bg-blue-600 text-white text-sm px-3 py-1.5 rounded-md hover:bg-blue-700">
                        + Tambah Buku
                    </a>
                </div>

                                {{-- Filter & Search --}}
                <form method="GET" class="mb-4 flex flex-col sm:flex-row sm:flex-wrap gap-2 sm:gap-3">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul / pengarang / kategori..."
                           class="w-full sm:w-64 border border-gray-300 rounded-md px-3 py-1.5 text-sm">

                    <div class="flex gap-2">
                        <select name="kategori_id"
                                class="flex-1 min-w-0 border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori_id')==$kat->id?'selected':'' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-gray-600 text-white px-3 py-1.5 rounded-md text-sm">Cari</button>
                        <a href="{{ route('pengurus.buku.index') }}"
                           class="bg-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-sm">Reset</a>
                    </div>
                </form>

                                {{-- TAMPILAN HP: kartu --}}
                <div class="md:hidden space-y-3">
                    @forelse($buku as $item)
                    <div class="border rounded-lg p-3 flex gap-3">
                        @if($item->cover)
                            <img src="{{ asset('storage/'.$item->cover) }}"
                                 class="h-20 w-14 object-cover rounded shrink-0">
                        @else
                            <div class="h-20 w-14 rounded bg-gray-100 text-gray-400 text-[10px] flex items-center justify-center text-center shrink-0">
                                No cover
                            </div>
                        @endif

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 leading-snug">{{ $item->judul }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $item->pengarang }}</p>

                            <div class="flex items-center gap-2 mt-1.5 text-xs">
                                <span class="bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                    {{ $item->kategori?->nama ?? '-' }}
                                </span>
                                <span class="{{ $item->stok == 0 ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                                    Stok: {{ $item->stok }}
                                </span>
                            </div>

                            <div class="flex items-center gap-4 mt-2 pt-2 border-t text-xs">
                                <a href="{{ route('pengurus.buku.show', $item) }}" class="text-gray-600">Detail</a>
                                <a href="{{ route('pengurus.buku.edit', $item) }}" class="text-blue-600">Edit</a>
                                <form action="{{ route('pengurus.buku.destroy', $item) }}"
                                      method="POST" data-judul="{{ $item->judul }}" onsubmit="return bukaModalHapus(this)">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-sm text-gray-500 py-4">Belum ada data buku</p>
                    @endforelse
                </div>

                {{-- TAMPILAN LAYAR BESAR: tabel --}}
                <div class="hidden md:block overflow-x-auto">
                <table class="w-full text-sm text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">No</th>
                            <th class="px-4 py-2 border">Cover</th>
                            <th class="px-4 py-2 border">Judul</th>
                            <th class="px-4 py-2 border">Pengarang</th>
                            <th class="px-4 py-2 border">Kategori</th>
                            <th class="px-4 py-2 border">Stok</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buku as $item)
                        <tr>
                            <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border">
                                @if($item->cover)
                                    <img src="{{ asset('storage/'.$item->cover) }}"
                                         class="h-12 w-10 object-cover">
                                @else
                                    <span class="text-gray-400 text-xs">No cover</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 border">{{ $item->judul }}</td>
                            <td class="px-4 py-2 border">{{ $item->pengarang }}</td>
                            <td class="px-4 py-2 border">{{ $item->kategori?->nama ?? '-' }}</td>
                            <td class="px-4 py-2 border">
                                <span class="{{ $item->stok == 0 ? 'text-red-600 font-semibold' : 'text-green-600' }}">
                                    {{ $item->stok }}
                                </span>
                            </td>
                            <td class="px-4 py-2 border">
                                <div class="flex gap-1">
                                    <a href="{{ route('pengurus.buku.show', $item) }}"
                                       class="text-gray-600 hover:underline text-xs">Detail</a>
                                    <a href="{{ route('pengurus.buku.edit', $item) }}"
                                       class="text-blue-600 hover:underline text-xs">Edit</a>
                                    <form action="{{ route('pengurus.buku.destroy', $item) }}"
                                          method="POST" class="inline"
                                          data-judul="{{ $item->judul }}" onsubmit="return bukaModalHapus(this)">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline text-xs">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center px-4 py-4 text-gray-500">
                                Belum ada data buku
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                <div class="mt-4">{{ $buku->links() }}</div>
            </div>
        </div>
    </div>
        {{-- Modal Konfirmasi Hapus --}}
    <div id="modalHapus" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm px-4"
         onclick="if(event.target === this) tutupModalHapus()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-5 sm:p-6 text-center">
            <div class="mx-auto w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mb-3">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-gray-800">Hapus buku ini?</h3>
            <p class="text-sm text-gray-500 mt-1">
                <span id="hapusJudul" class="font-semibold text-gray-700"></span>
                akan dihapus permanen dan tidak bisa dikembalikan.
            </p>
            <div class="flex gap-2 mt-5">
                <button type="button" onclick="tutupModalHapus()"
                        class="flex-1 bg-gray-100 text-gray-700 text-sm font-medium px-4 py-2 rounded-lg hover:bg-gray-200">
                    Batal
                </button>
                <button type="button" onclick="lanjutHapus()"
                        class="flex-1 bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg hover:bg-red-700">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <script>
        let formHapus = null;

        function bukaModalHapus(form) {
            formHapus = form;
            document.getElementById('hapusJudul').textContent = form.dataset.judul;
            document.getElementById('modalHapus').classList.remove('hidden');
            return false; // batalkan submit dulu, tunggu konfirmasi
        }

        function tutupModalHapus() {
            document.getElementById('modalHapus').classList.add('hidden');
            formHapus = null;
        }

        function lanjutHapus() {
            if (formHapus) formHapus.submit();
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') tutupModalHapus();
        });
    </script>
</x-app-layout>