<x-app-layout>
    <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Pinjam Offline (Anggota Datang Langsung)
        </h2>
    </x-slot>
    <div class="py-4 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
            @endif

            <div class="bg-white shadow-sm rounded-xl sm:rounded-lg p-4 sm:p-6">
                <p class="text-xs sm:text-sm text-gray-500 mb-4">
                    Gunakan form ini untuk anggota (siswa/guru) yang datang langsung ke perpustakaan
                    tanpa membuka aplikasi/website, misalnya karena lupa membawa HP. Aturannya tetap sama
                    seperti peminjaman online — admin tetap perlu mengupload foto bukti pengambilan buku.
                    Form ini hanya jalan pintas supaya anggota tidak perlu login dari HP mereka sendiri.
                    Ketik nama atau judul untuk mencari.
                </p>

                <form method="POST" action="{{ route('pengurus.peminjaman.offline.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Anggota</label>
                        <select name="user_id" id="selectAnggota" required
                                class="w-full border rounded px-3 py-2 text-sm">
                        </select>
                        @error('user_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Buku</label>
                        <select name="buku_id" id="selectBuku" required
                                class="w-full border rounded px-3 py-2 text-sm">
                        </select>
                        @error('buku_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Foto Pengambilan <span class="text-red-500">*</span></label>
                        <input type="file" name="bukti_pengambilan" accept="image/*" required
                               class="block w-full text-xs sm:text-sm text-gray-500
                                      file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0
                                      file:text-xs sm:file:text-sm file:font-medium file:bg-gray-100 file:text-gray-700
                                      hover:file:bg-gray-200">
                        <p class="text-xs text-gray-400 mt-1">Foto anggota saat mengambil buku di perpustakaan, sama seperti bukti pengambilan online.</p>
                        @error('bukti_pengambilan') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-2">
                        <a href="{{ route('pengurus.peminjaman.index') }}"
                           class="bg-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-400 text-sm text-center">Batal</a>
                        <button type="submit"
                                class="bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 text-sm">
                            Simpan Peminjaman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Select2: library dropdown pencarian --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
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
            $('#selectAnggota').select2({
                placeholder: '-- Cari nama anggota --',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: "{{ route('pengurus.peminjaman.offline.cari-anggota') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });

            $('#selectBuku').select2({
                placeholder: '-- Cari judul buku --',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: "{{ route('pengurus.peminjaman.offline.cari-buku') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return { q: params.term };
                    },
                    processResults: function (data) {
                        return { results: data.results };
                    },
                    cache: true
                }
            });
        });
    </script>
</x-app-layout>