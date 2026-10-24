<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}" />

        <!-- Email -->
        <div class="mb-4">
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                name="email" :value="old('email', $request->email)" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <x-input-label for="password" value="Password Baru" />
            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                    type="password" name="password" required
                    placeholder="Min. 8 karakter" />
                <button type="button" onclick="togglePassword('password', this)"
                    class="absolute inset-y-0 right-0 px-3 mt-1 text-gray-500 hover:text-gray-700">
                    🙈
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <x-input-label for="password_confirmation" value="Konfirmasi Password" />
            <div class="relative">
                <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10"
                    type="password" name="password_confirmation" required
                    placeholder="Ulangi password baru" />
                <button type="button" onclick="togglePassword('password_confirmation', this)"
                    class="absolute inset-y-0 right-0 px-3 mt-1 text-gray-500 hover:text-gray-700">
                    🙈
                </button>
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>Reset Password</x-primary-button>
        </div>
    </form>

    <script>
    function togglePassword(fieldId, btn) {
        const field = document.getElementById(fieldId);
        if (field.type === 'password') {
            field.type = 'text';
            btn.textContent = '🐵';
        } else {
            field.type = 'password';
            btn.textContent = '🙈';
        }
    }
    </script>
</x-guest-layout>