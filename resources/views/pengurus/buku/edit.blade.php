<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">Edit Buku</h2>
    </x-slot>
    <div class="py-3 sm:py-12">
        <div class="max-w-3xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">
                <form action="{{ route('pengurus.buku.update', $buku) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')

                    @if($permintaanMenunggu)
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded-lg mb-3 text-xs">
                            ⏳ Kamu punya pengajuan edit yang masih <b>menunggu persetujuan</b> Kepala Perpustakaan untuk field:
                            {{ collect($permintaanMenunggu->field_diminta)->map(fn($f) => \App\Models\PermintaanEditBuku::labelField($f))->join(', ') }}.
                        </div>
                    @elseif($permintaanAktif)
                        <div class="bg-green-50 border border-green-200 text-green-800 px-3 py-2 rounded-lg mb-3 text-xs">
                            ✅ Izin edit disetujui untuk field: {{ collect($fieldTerbuka)->map(fn($f) => \App\Models\PermintaanEditBuku::labelField($f))->join(', ') }}.
                            Field tersebut sekarang bisa diedit. Izin ini cuma berlaku <b>1x sampai kamu klik Update</b>.
                        </div>
                    @endif

                    {{-- Judul --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Judul Buku</label>
                        @if(in_array('judul', $fieldTerbuka))
                            <input type="text" name="judul" value="{{ old('judul', $buku->judul) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                        @else
                            <input type="text" value="{{ $buku->judul }}" disabled
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" name="judul" value="{{ $buku->judul }}">
                        @endif
                        @error('judul') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    {{-- Pengarang --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Pengarang</label>
                        @if(in_array('pengarang', $fieldTerbuka))
                            <input type="text" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                        @else
                            <input type="text" value="{{ $buku->pengarang }}" disabled
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" name="pengarang" value="{{ $buku->pengarang }}">
                        @endif
                        @error('pengarang') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    {{-- Kategori (dropdown) --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Kategori</label>
                        @if(in_array('kategori_id', $fieldTerbuka))
                            <select name="kategori_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('kategori_id', $buku->kategori_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="mt-1">
                                <a href="{{ route('pengurus.kategori.create') }}"
                                   class="text-sm text-sky-600 hover:text-sky-700 font-medium">
                                    + Tambah Kategori Baru
                                </a>
                            </div>
                        @else
                            <input type="text" value="{{ $buku->kategori->nama ?? '-' }}" disabled
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" name="kategori_id" value="{{ $buku->kategori_id }}">
                        @endif
                        @error('kategori_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- ISBN --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">ISBN</label>
                        @if(in_array('isbn', $fieldTerbuka))
                            <input type="text" name="isbn" value="{{ old('isbn', $buku->isbn) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                        @else
                            <input type="text" value="{{ $buku->isbn }}" disabled
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" name="isbn" value="{{ $buku->isbn }}">
                        @endif
                    </div>

                    {{-- Penerbit --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Penerbit</label>
                        @if(in_array('penerbit', $fieldTerbuka))
                            <input type="text" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                        @else
                            <input type="text" value="{{ $buku->penerbit }}" disabled
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" name="penerbit" value="{{ $buku->penerbit }}">
                        @endif
                    </div>

                    {{-- Tahun Terbit --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Tahun Terbit</label>
                        @if(in_array('tahun_terbit', $fieldTerbuka))
                            <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                        @else
                            <input type="number" value="{{ $buku->tahun_terbit }}" disabled
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" name="tahun_terbit" value="{{ $buku->tahun_terbit }}">
                        @endif
                    </div>

                    {{-- Rak (SELALU AKTIF, tidak dikunci) --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Rak</label>
                        <input type="text" name="rak" value="{{ old('rak', $buku->rak) }}"
                               placeholder="Contoh: Rak A-02"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                    </div>

                    {{-- Sinopsis (SELALU AKTIF, tidak dikunci) --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Sinopsis</label>
                        <textarea name="sinopsis" rows="4"
                                  class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">{{ old('sinopsis', $buku->sinopsis) }}</textarea>
                    </div>

                    {{-- Stok --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Stok</label>
                        @if(in_array('stok', $fieldTerbuka))
                            <input type="number" name="stok" value="{{ old('stok', $buku->stok) }}" min="0"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                        @else
                            <input type="number" value="{{ $buku->stok }}" disabled
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100 text-gray-500 cursor-not-allowed">
                            <input type="hidden" name="stok" value="{{ $buku->stok }}">
                        @endif
                        @error('stok') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    {{-- Cover --}}
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Cover Buku</label>
                        @if($buku->cover)
                            <img src="{{ asset('storage/'.$buku->cover) }}" class="h-20 rounded mb-2 border">
                        @endif
                        @if(in_array('cover', $fieldTerbuka))
                            <p class="text-xs text-gray-400 mb-1">Upload baru untuk mengganti cover</p>
                            <input type="file" name="cover" accept="image/*"
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
                        @else
                            <input type="file" disabled
                                   class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm bg-gray-100 cursor-not-allowed">
                        @endif
                        @error('cover') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tombol Ajukan Perubahan --}}
                    <div class="mb-4">
                        <button type="button" onclick="document.getElementById('modalAjukan').classList.remove('hidden')"
                                class="text-xs sm:text-sm text-amber-600 hover:underline text-left">
                            🔒 Perlu ubah data yang terkunci? Ajukan Perubahan
                        </button>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex gap-2 sm:justify-end">
                        <a href="{{ route('pengurus.buku.index') }}"
                           class="flex-1 sm:flex-none text-center bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-md hover:bg-gray-300">Batal</a>
                        <button type="submit"
                                class="flex-1 sm:flex-none bg-blue-600 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-700">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Ajukan Perubahan --}}
    <div id="modalAjukan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white rounded-xl shadow-2xl p-4 sm:p-6 max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <h3 class="font-bold text-gray-800 mb-3">Ajukan Perubahan Data Terkunci</h3>
            <form method="POST" action="{{ route('pengurus.buku.ajukan-edit', $buku) }}">
                @csrf
                <div class="space-y-2 mb-4">
                    @foreach($fieldTerkunci as $field)
                        @if(!in_array($field, $fieldTerbuka))
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="field_diminta[]" value="{{ $field }}">
                            {{ \App\Models\PermintaanEditBuku::labelField($field) }}
                        </label>
                        @endif
                    @endforeach
                </div>
                <div class="mb-3">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Alasan (wajib, min. 10 karakter)</label>
                    <textarea name="alasan" rows="3" required minlength="10"
                              class="w-full border rounded px-3 py-2 text-sm"
                              placeholder="Jelaskan kenapa perubahan ini mendesak..."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('modalAjukan').classList.add('hidden')"
                            class="bg-gray-300 text-gray-700 px-4 py-2 rounded text-sm">Batal</button>
                    <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded text-sm hover:bg-amber-700">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>