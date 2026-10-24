<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profil Saya</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-xl text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 h-32 relative">
                    <div class="absolute -bottom-14 left-1/2 -translate-x-1/2">
                        @if($user->foto)
                            <button type="button" onclick="document.getElementById('modalFotoProfilPengurus').classList.remove('hidden')"
                                    class="block w-28 h-28 rounded-2xl shadow-lg border-4 border-white overflow-hidden">
                                <img src="{{ asset('storage/'.$user->foto) }}"
                                     class="w-full h-full object-cover">
                            </button>
                        @else
                            <div class="w-28 h-28 rounded-2xl bg-white shadow-lg border-4 border-white
                                        flex items-center justify-center
                                        bg-gradient-to-br from-sky-400 to-indigo-600
                                        text-white text-3xl font-bold">
                                {{ collect(explode(' ', $user->name))->map(fn($w) => strtoupper($w[0]))->take(2)->implode('') }}
                            </div>
                        @endif
                    </div>
                </div>
                {{-- LABEL TULISAN DI BAWAH FOTO --}}
                <div class="pt-16 pb-6 px-8 text-center">
                    <h3 class="text-xl font-bold text-gray-800">{{ $user->name }}</h3>
                    <span class="inline-block mt-1 px-3 py-0.5 bg-blue-50 text-blue-600 text-xs font-semibold rounded-full">
                        {{ $user->role === 'pengurus' ? 'Petugas' : ucfirst($user->role) }}
                    </span>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wide mb-4">Edit Profil</h4>

                <form method="POST" action="{{ route('pengurus.profil.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama / Username</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                               class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Profil</label>
                        <input type="file" name="foto" accept="image/*"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm">
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah foto.</p>
                    </div>

                    <hr class="my-4">

                    <p class="text-xs font-semibold text-gray-500 uppercase">Ganti Password (opsional)</p>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation"
                               class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-sky-300 focus:border-sky-400 outline-none">
                    </div>

                    <button type="submit"
                            class="w-full bg-sky-600 hover:bg-sky-700 text-white rounded-xl py-2.5 font-semibold transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- MODAL LIHAT FOTO PROFIL --}}
@if($user->foto)
<div id="modalFotoProfilPengurus" class="hidden fixed inset-0 z-50 flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60"
         onclick="document.getElementById('modalFotoProfilPengurus').classList.add('hidden')"></div>
    <div class="relative z-10 max-w-lg w-full mx-4">
        <img src="{{ asset('storage/'.$user->foto) }}"
             class="w-full rounded-2xl shadow-2xl object-contain max-h-[80vh] mx-auto">
        <button onclick="document.getElementById('modalFotoProfilPengurus').classList.add('hidden')"
                class="absolute -top-3 -right-3 bg-white text-gray-800 rounded-full w-9 h-9 flex items-center justify-center shadow hover:bg-gray-100 font-bold">
            ✕
        </button>
    </div>
</div>
@endif
</x-app-layout>