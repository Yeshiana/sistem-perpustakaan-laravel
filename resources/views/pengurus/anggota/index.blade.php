<x-app-layout>
    <x-slot name="header">
        <h2 class="hidden sm:block font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Data Anggota
        </h2>
    </x-slot>
    <div class="py-4 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            <div class="bg-white shadow-sm rounded-xl sm:rounded-lg p-3 sm:p-6">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 sm:gap-0 mb-3 sm:mb-4">
                    <h3 class="text-base sm:text-lg font-semibold">Daftar Anggota</h3>
                    <a href="{{ route('pengurus.anggota.create') }}"
                       class="bg-blue-600 text-white px-3 sm:px-4 py-2 rounded text-xs sm:text-sm text-center hover:bg-blue-700">
                        + Tambah Anggota
                    </a>
                </div>

                {{-- Filter & Search --}}
                <form method="GET" class="flex flex-col sm:flex-row gap-2 sm:gap-3 mb-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama / NISN / email..."
                           class="border rounded px-3 py-2 w-full sm:w-64 text-xs sm:text-sm">
                    <select name="role" class="border rounded px-3 py-2 text-xs sm:text-sm w-full sm:w-auto">
                        <option value="">Semua Role</option>
                        <option value="siswa" {{ request('role')=='siswa'?'selected':'' }}>Siswa</option>
                        <option value="guru" {{ request('role')=='guru'?'selected':'' }}>Guru</option>
                    </select>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 sm:flex-none bg-gray-600 text-white px-4 py-2 rounded text-xs sm:text-sm">
                            Cari
                        </button>
                        <a href="{{ route('pengurus.anggota.index') }}"
                           class="flex-1 sm:flex-none bg-gray-200 text-gray-700 px-4 py-2 rounded text-xs sm:text-sm text-center">
                            Reset
                        </a>
                    </div>
                </form>

                <div class="overflow-x-auto -mx-3 sm:mx-0">
                <table class="w-full text-xs sm:text-sm text-left border">
                    <thead class="bg-sky-50">
                        <tr>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold text-center whitespace-nowrap">No</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold text-center whitespace-nowrap">Foto</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold text-center">Nama</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold text-center whitespace-nowrap">Role</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold text-center whitespace-nowrap">NISN / Email</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold text-center">Kelas / Jabatan</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold text-center whitespace-nowrap">Status</th>
                            <th class="px-2 sm:px-4 py-2 border border-sky-100 text-sky-700 font-semibold text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($anggota as $item)
                        <tr>
                            <td class="px-2 sm:px-4 py-2 border">{{ $loop->iteration }}</td>
                            <td class="px-2 sm:px-4 py-2 border">
                                @if($item->foto)
                                    <img src="{{ asset('storage/'.$item->foto) }}"
                                         class="h-7 w-7 sm:h-10 sm:w-10 rounded-full object-cover">
                                @else
                                    <div class="h-7 w-7 sm:h-10 sm:w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 text-[9px] sm:text-xs">
                                        N/A
                                    </div>
                                @endif
                            </td>
                            <td class="px-2 sm:px-4 py-2 border">{{ $item->name }}</td>
                            <td class="px-2 sm:px-4 py-2 border whitespace-nowrap">
                                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-semibold
                                    {{ $item->role == 'siswa' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                    {{ ucfirst($item->role) }}
                                </span>
                            </td>
                            <td class="px-2 sm:px-4 py-2 border whitespace-nowrap">
                                {{ $item->nisn ?? $item->email }}
                            </td>
                            <td class="px-2 sm:px-4 py-2 border max-w-[70px] sm:max-w-none">
                                {{ $item->kelas ?? $item->jabatan ?? '-' }}
                            </td>
                            <td class="px-2 sm:px-4 py-2 border whitespace-nowrap">
                                <span class="px-1.5 sm:px-2 py-0.5 sm:py-1 rounded text-[10px] sm:text-xs font-semibold
                                    {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-2 sm:px-4 py-2 border">
                                <div class="flex items-center gap-1.5">
                                    <a href="{{ route('pengurus.anggota.show', $item) }}"
                                       title="Detail"
                                       class="w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('pengurus.anggota.edit', $item) }}"
                                       title="Edit"
                                       class="w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('pengurus.anggota.toggle-aktif', $item) }}"
                                          method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            title="{{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                            class="w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center rounded-lg transition
                                                {{ $item->is_active ? 'bg-orange-50 text-orange-600 hover:bg-orange-100' : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                                            @if($item->is_active)
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.36 6.64a9 9 0 11-12.73 0M12 3v9"/>
                                                </svg>
                                            @else
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>

                                    <form id="form-hapus-{{ $item->id }}" action="{{ route('pengurus.anggota.destroy', $item) }}"
                                    method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            title="Hapus"
                                            onclick="openHapusModal('form-hapus-{{ $item->id }}', '{{ addslashes($item->name) }}')"
                                            class="w-6 h-6 sm:w-7 sm:h-7 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center px-4 py-4 text-gray-500">
                                Belum ada data anggota
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
                <div class="mt-4">{{ $anggota->links() }}</div>
            </div>
        </div>
    </div>



{{-- MODAL KONFIRMASI HAPUS --}}
<div id="hapusModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 opacity-0 transition-opacity duration-200">

    <div id="hapusModalBox"
         class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4 p-6 text-center
                transform scale-95 transition-transform duration-200">

        <div class="mx-auto w-14 h-14 rounded-full bg-red-50 flex items-center justify-center mb-4">
            <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </div>

        <h2 class="text-lg font-bold text-gray-800">Hapus anggota ini?</h2>
        <p class="text-sm text-gray-500 mt-1">
            Data <span id="hapusNamaAnggota" class="font-semibold text-gray-700"></span>
            akan dihapus permanen dan tidak bisa dikembalikan.
        </p>

        <div class="grid grid-cols-2 gap-3 mt-6">
            <button type="button" onclick="closeHapusModal()"
                    class="border border-gray-200 text-gray-600 rounded-xl py-2.5 text-sm font-medium
                           hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="button" onclick="confirmHapus()"
                    class="bg-red-600 text-white rounded-xl py-2.5 text-sm font-semibold
                           hover:bg-red-700 transition">
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
let formIdToDelete = null;

function openHapusModal(formId, nama) {
    formIdToDelete = formId;
    document.getElementById('hapusNamaAnggota').innerText = nama;

    const modal = document.getElementById('hapusModal');
    const box = document.getElementById('hapusModalBox');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    requestAnimationFrame(() => {
        modal.classList.remove('opacity-0');
        box.classList.remove('scale-95');
        box.classList.add('scale-100');
    });
}

function closeHapusModal() {
    const modal = document.getElementById('hapusModal');
    const box = document.getElementById('hapusModalBox');

    modal.classList.add('opacity-0');
    box.classList.remove('scale-100');
    box.classList.add('scale-95');

    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 200);

    formIdToDelete = null;
}

function confirmHapus() {
    if (formIdToDelete) {
        document.getElementById(formIdToDelete).submit();
    }
}
</script>

</x-app-layout>