<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
            Tambah Buku
        </h2>
    </x-slot>
    <div class="py-3 sm:py-12">
        <div class="max-w-3xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">
                <form action="{{ route('pengurus.buku.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Judul Buku <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" value="{{ old('judul') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                        @error('judul') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Pengarang <span class="text-red-500">*</span></label>
                        <input type="text" name="pengarang" value="{{ old('pengarang') }}"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                        @error('pengarang') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-3">
    <label class="block text-xs sm:text-sm font-medium text-gray-700">
    Kategori <span class="text-red-500">*</span>
</label>
    <select
        name="kategori_id"
        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm"
        required>

        <option value="">-- Pilih Kategori --</option>

        @foreach($kategori as $item)
            <option
                value="{{ $item->id }}"
                {{ old('kategori_id') == $item->id ? 'selected' : '' }}>
                {{ $item->nama }}
            </option>
        @endforeach

    </select>
    <div class="mt-1">
    <a href="{{ route('pengurus.kategori.create') }}"
       class="text-xs sm:text-sm text-sky-600 hover:text-sky-700 font-medium">
        + Tambah Kategori Baru
    </a>
</div>

    @error('kategori_id')
        <p class="text-red-500 text-xs mt-1">
            {{ $message }}
        </p>
    @enderror
</div>
                    <div class="mb-3">
    <label class="block text-xs sm:text-sm font-medium text-gray-700">ISBN</label>
    <input type="text"
           name="isbn"
           value="{{ old('isbn') }}"
           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
</div>

<div class="mb-3">
    <label class="block text-xs sm:text-sm font-medium text-gray-700">Penerbit</label>
    <input type="text"
           name="penerbit"
           value="{{ old('penerbit') }}"
           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
</div>

<div class="mb-3">
    <label class="block text-xs sm:text-sm font-medium text-gray-700">Tahun Terbit</label>
    <input type="number"
           name="tahun_terbit"
           value="{{ old('tahun_terbit') }}"
           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
</div>

<div class="mb-3">
    <label class="block text-xs sm:text-sm font-medium text-gray-700">Rak</label>
    <input type="text"
           name="rak"
           value="{{ old('rak') }}"
           placeholder="Contoh : Rak A-02"
           class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">
</div>

<div class="mb-3">
    <label class="block text-xs sm:text-sm font-medium text-gray-700">Sinopsis</label>
    <textarea
        name="sinopsis"
        rows="5"
        class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm">{{ old('sinopsis') }}</textarea>
</div>


                    <div class="mb-3">
                       <label class="block text-xs sm:text-sm font-medium text-gray-700">Stok <span class="text-red-500">*</span></label>
                        <input type="number" name="stok" value="{{ old('stok', 0) }}" min="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1.5 text-sm" required>
                        @error('stok') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700">Cover Buku</label>
                        <input type="file" name="cover" accept="image/*"
                               class="mt-1 block w-full text-xs text-gray-500
                                      file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0
                                      file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700
                                      hover:file:bg-gray-200">
                        @error('cover') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex gap-2 sm:justify-end">
                        <a href="{{ route('pengurus.buku.index') }}"
                           class="flex-1 sm:flex-none text-center bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded-md hover:bg-gray-300">Batal</a>
                        <button type="submit"
                                class="flex-1 sm:flex-none bg-blue-600 text-white text-sm px-4 py-2 rounded-md hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>