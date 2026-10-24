<x-guest-layout>
    <div class="mb-4 text-center">
        <h2 class="text-xl font-bold text-gray-800">🔐 Lupa Password?</h2>
        <p class="text-sm text-gray-600 mt-2">Masukkan email yang didaftarkan, kami akan kirim link reset password.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email -->
        <div class="mb-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                name="email" :value="old('email')" required autofocus
                placeholder="Masukkan email kamu" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:underline">
                ← Kembali ke Login
            </a>
            <x-primary-button>
                Kirim Link Reset
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>