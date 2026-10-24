<x-guest-layout>
    <div class="text-center mb-6">
        <h2 class="text-xl font-bold text-gray-800">👨‍🏫 Daftar sebagai Guru</h2>
    </div>

    <form method="POST" action="{{ route('register.guru.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- Nama -->
        <div class="mb-4">
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" type="text" name="name" 
                :value="old('name')" class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mb-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" 
                :value="old('email')" class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
<div class="mb-4">
    <x-input-label for="password" value="Password" />
    <div class="relative">
        <x-text-input id="password" type="password" name="password"
            class="block mt-1 w-full pr-10" required
            placeholder="Min. 8 Karakter" />
        <button type="button" onclick="togglePassword('password', this)"
            class="absolute inset-y-0 right-0 px-3 mt-1 text-gray-500 hover:text-gray-700">
            🙈
        </button>
    </div>
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

        <!-- Konfirmasi Password -->
<div class="mb-4">
    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
    <div class="relative">
        <x-text-input id="password_confirmation" type="password"
            name="password_confirmation" class="block mt-1 w-full pr-10" required />
        <button type="button" onclick="togglePassword('password_confirmation', this)"
            class="absolute inset-y-0 right-0 px-3 mt-1 text-gray-500 hover:text-gray-700">
            🙈
        </button>
    </div>
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>


        <!-- TTL -->
        <div class="mb-4">
            <x-input-label for="ttl" value="Tanggal Lahir" />
            <x-text-input id="ttl" type="date" name="ttl" 
                :value="old('ttl')" class="block mt-1 w-full" required />
            <x-input-error :messages="$errors->get('ttl')" class="mt-2" />
        </div>

        <!-- Alamat -->
        <div class="mb-4">
            <x-input-label for="alamat" value="Alamat" />
            <textarea id="alamat" name="alamat" rows="3"
                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                required>{{ old('alamat') }}</textarea>
            <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
        </div>

        <!-- Foto -->
        <div class="mb-4">
            <x-input-label for="foto" value="Foto Profil" />
            <input id="foto" type="file" name="foto" accept="image/*"
                class="block mt-1 w-full border border-gray-300 rounded-md px-3 py-2" />
            <x-input-error :messages="$errors->get('foto')" class="mt-2" />
        </div>

        <div class="flex justify-between items-center mt-4">
            <a href="{{ route('register.pilih') }}" class="text-sm text-gray-600 hover:underline">
                ← Kembali
            </a>
            <x-primary-button>Daftar</x-primary-button>
        </div>
    </form>
    <script>
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        btn.textContent = '🐵';  // mata terbuka → password keliatan
    } else {
        field.type = 'password';
        btn.textContent = '🙈';  // tutup mata → password tersembunyi
    }
}
</script>

</x-guest-layout>