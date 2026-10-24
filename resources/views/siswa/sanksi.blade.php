<x-app-layout>
        <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Sanksi Saya
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

        <div class="bg-white shadow-sm rounded-xl p-3 sm:p-6">
         @forelse($sanksi as $item)
         <div class="border rounded-xl p-3 sm:p-4 mb-3 sm:mb-4 {{ $item->status == 'selesai' ? 'border-green-200 bg-green-50' : 'border-orange-200 bg-orange-50' }}">
         <div class="flex justify-between items-start gap-2">
        <div>
                                <p class="font-semibold text-gray-800 text-sm sm:text-base">{{ $item->peminjaman->buku->judul }}</p>
                                <p class="text-[10px] sm:text-xs text-gray-500 mt-1">
                                    {{ $item->jenis == 'keterlambatan' ? '⏰ Keterlambatan' : '📚 Buku Rusak' }}
                                </p>
                                <p class="text-xs sm:text-sm text-gray-600 mt-1.5 sm:mt-2">{{ $item->keterangan }}</p>
                            </div>
            @php
                                $statusColor = [
                                    'menunggu'            => 'bg-yellow-100 text-yellow-700',
                                    'menunggu_verifikasi' => 'bg-orange-100 text-orange-700',
                                    'selesai'             => 'bg-green-100 text-green-700',
                                    'ditolak'             => 'bg-red-100 text-red-700',
                                ][$item->status] ?? 'bg-gray-100 text-gray-700';
                                $statusLabel = [
                                    'menunggu'            => 'Menunggu Bukti',
                                    'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                    'selesai'             => 'Selesai',
                                    'ditolak'             => 'Ditolak',
                                ][$item->status] ?? $item->status;
                            @endphp
                            <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-semibold whitespace-nowrap shrink-0 {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        {{-- Upload bukti --}}
                                               @if(in_array($item->status, ['menunggu', 'ditolak']))
                            <form action="{{ route('siswa.sanksi.upload', $item) }}"
                                  method="POST" enctype="multipart/form-data"
                                  class="mt-3 sm:mt-4 flex flex-col sm:flex-row gap-2 sm:items-center">
                                @csrf
                                <input type="file" name="bukti_sanksi"
                                       accept="image/*" required
                                       class="text-[10px] sm:text-xs border rounded px-2 py-1 w-full sm:w-auto">
                                <button type="submit"
                                    class="bg-indigo-500 text-white px-3 py-1.5 sm:py-1 rounded text-[10px] sm:text-xs hover:bg-indigo-600 shrink-0">
                                    Upload Bukti
                                </button>
                            </form>
                            @if($item->status == 'ditolak')
                                <p class="text-red-500 text-[10px] sm:text-xs mt-1">⚠ Bukti sebelumnya ditolak, harap upload ulang.</p>
                            @endif
                        @elseif($item->bukti_sanksi)
                            <div class="mt-2 sm:mt-3">
                                <p class="text-[10px] sm:text-xs text-gray-500 mb-1">Bukti yang diupload:</p>
                                <img src="{{ asset('storage/'.$item->bukti_sanksi) }}"
                                     class="w-16 h-16 sm:w-24 sm:h-24 object-cover rounded border">
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-5xl mb-3">✅</div>
                        <p class="text-gray-400 text-sm">Tidak ada sanksi aktif</p>
                    </div>
                @endforelse

                <div class="mt-4">{{ $sanksi->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>