<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Persetujuan Edit Data Buku
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-sm text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">Buku</th>
                            <th class="px-4 py-2 border">Diajukan Oleh</th>
                            <th class="px-4 py-2 border">Field Diminta</th>
                            <th class="px-4 py-2 border">Alasan</th>
                            <th class="px-4 py-2 border">Tanggal</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($permintaan as $item)
                        <tr>
                            <td class="px-4 py-2 border">{{ $item->buku->judul }}</td>
                            <td class="px-4 py-2 border">{{ $item->pengaju->name }}</td>
                            <td class="px-4 py-2 border">
                                {{ collect($item->field_diminta)->map(fn($f) => \App\Models\PermintaanEditBuku::labelField($f))->join(', ') }}
                            </td>
                            <td class="px-4 py-2 border text-xs text-gray-600">{{ $item->alasan }}</td>
                            <td class="px-4 py-2 border text-xs">{{ $item->created_at->format('d M Y H:i') }}</td>
                            <td class="px-4 py-2 border">
                                @if($item->status == 'menunggu')
                                    <form action="{{ route('kepala.permintaan-edit-buku.setujui', $item) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button class="bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600">Setujui</button>
                                    </form>
                                    <button onclick="document.getElementById('modal-tolak-{{ $item->id }}').classList.remove('hidden')"
                                            class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 ml-1">Tolak</button>

                                    <div id="modal-tolak-{{ $item->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
                                        <div class="bg-white rounded-xl shadow-2xl p-6 max-w-sm w-full mx-4">
                                            <h3 class="font-bold text-gray-800 mb-2">Tolak Pengajuan?</h3>
                                            <form action="{{ route('kepala.permintaan-edit-buku.tolak', $item) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <textarea name="catatan_kepala" rows="3" placeholder="Alasan penolakan (opsional)"
                                                          class="w-full border rounded px-3 py-2 text-sm mb-3"></textarea>
                                                <div class="flex justify-end gap-2">
                                                    <button type="button" onclick="document.getElementById('modal-tolak-{{ $item->id }}').classList.add('hidden')"
                                                            class="bg-gray-300 px-4 py-2 rounded text-sm">Batal</button>
                                                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded text-sm">Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $item->status == 'disetujui' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center px-4 py-4 text-gray-500">Tidak ada pengajuan</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $permintaan->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>