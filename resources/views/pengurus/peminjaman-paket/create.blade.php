<x-app-layout>
    <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Pinjam Buku Paket
        </h2>
    </x-slot>

    <div class="py-4 sm:py-8">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-xl sm:rounded-lg p-4 sm:p-6">
                <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
                    Untuk peminjaman buku paket (misal buku pelajaran satu kelas). Peminjam tidak perlu punya akun anggota perpustakaan.
                </p>

                <form action="{{ route('pengurus.peminjaman-paket.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Buku <span class="text-red-500">*</span></label>
                        <select name="buku_id" id="buku-select" class="w-full border rounded-lg px-3 py-2 text-sm" required style="width: 100%;"></select>
                        @error('buku_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Buku Dipinjam <span class="text-red-500">*</span></label>
                        <input type="number" name="jumlah" min="1" value="{{ old('jumlah') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: 30" required>
                        @error('jumlah') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Peminjam <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Nama yang bertanggung jawab (misal ketua kelas)" required>
                        @error('nama_peminjam') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kelas <span class="text-red-500">*</span></label>
                            <input type="text" name="kelas" value="{{ old('kelas') }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: X TKJ 1" required>
                            @error('kelas') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran <span class="text-red-500">*</span></label>
                            <input type="text" name="mata_pelajaran" value="{{ old('mata_pelajaran') }}"
                                   class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Contoh: Matematika" required>
                            @error('mata_pelajaran') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guru <span class="text-red-500">*</span></label>
                        <input type="text" name="guru" value="{{ old('guru') }}"
                               class="w-full border rounded-lg px-3 py-2 text-sm" placeholder="Nama guru mata pelajaran terkait" required>
                        @error('guru') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                       <div class="mb-4">
       <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Rencana Kembali <span class="text-red-500">*</span></label>
       <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali') }}"
              min="{{ date('Y-m-d') }}"
              class="w-full border rounded-lg px-3 py-2 text-sm" required>
       <p class="text-xs text-gray-500 mt-1">Tidak ada batas maksimal. Untuk pemakaian satu semester, isi dengan tanggal akhir semester.</p>
       @error('tgl_kembali') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
   </div>
   
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2 mt-6">
                        <a href="{{ route('pengurus.peminjaman.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-400 text-sm text-center">Batal</a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        .select2-container .select2-selection--single {
            height: 42px !important;
            display: flex;
            align-items: center;
            border-radius: 0.5rem !important;
            border-color: #d1d5db !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            font-size: 0.875rem;
            line-height: normal;
            padding-left: 0.75rem;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
    </style>
    <script>
        $(document).ready(function () {
            $('#buku-select').select2({
                placeholder: 'Cari judul buku...',
                ajax: {
                    url: '{{ route("pengurus.peminjaman-paket.cari-buku") }}',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({ q: params.term }),
                    processResults: data => ({ results: data.results })
                },
                minimumInputLength: 1
            });
        });
    </script>
</x-app-layout>