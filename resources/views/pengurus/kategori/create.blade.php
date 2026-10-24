<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">Tambah Kategori</h2>
    </x-slot>

    @php
        $kembali = url()->previous() === url()->current()
            ? route('pengurus.buku.index')
            : url()->previous();
    @endphp

    <div class="py-4 sm:py-8">
        <div class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm mb-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('pengurus.kategori.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kategori</label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                               placeholder="Contoh: Fiksi, Sains, Sejarah..."
                               class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base sm:text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none">
                    </div>

                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Deskripsi <span class="text-gray-400">(opsional)</span>
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="3"
                                  placeholder="Deskripsi singkat kategori..."
                                  class="w-full border border-gray-200 rounded-xl px-4 py-3 text-base sm:text-sm resize-none focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none">{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- ===== TOMBOL AKSI ===== --}}
                    <div class="pt-2 space-y-3">

                        {{-- 1. Simpan --}}
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 active:bg-sky-800 text-white px-4 py-3 rounded-xl text-sm font-semibold shadow-sm transition">
                            Simpan Kategori
                        </button>

                        {{-- 2. Lihat Kategori --}}
                        <a href="{{ route('pengurus.kategori.index') }}"
                           class="w-full flex items-center justify-center gap-2 border border-sky-200 bg-sky-50 text-sky-700 hover:bg-sky-100 px-4 py-3 rounded-xl text-sm font-medium transition">
                            Lihat Kategori
                        </a>

                        {{-- 3 & 4. Kembali + Batal --}}
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ $kembali }}"
                               class="flex items-center justify-center gap-1 border border-gray-200 text-gray-600 hover:bg-gray-50 px-4 py-3 rounded-xl text-sm font-medium transition">
                                ← Kembali
                            </a>
                            <a href="{{ route('pengurus.buku.index') }}"
                               class="flex items-center justify-center border border-red-100 bg-red-50 text-red-600 hover:bg-red-100 px-4 py-3 rounded-xl text-sm font-medium transition">
                                Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>