<x-guest-layout>

    <!-- Logo -->
    <div class="text-center mb-6">
        <img src="{{ asset('images/logoup45.png') }}" alt="UP45 Logo" class="mx-auto h-20 w-auto">
        <h2 class="mt-3 text-xl font-bold text-gray-800">Sistem Pengajuan Surat</h2>
        <p class="text-sm text-gray-500">Universitas Proklamasi 45</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Identifier -->
        <div>
            <label for="identifier" class="block font-semibold text-gray-700 mb-1">
                NIM / NIP / Email
            </label>
            <input id="identifier"
                   class="form-control w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                   type="text"
                   name="identifier"
                   :value="old('identifier')"
                   required autofocus
                   placeholder="Masukkan NIM, NIP, atau Email Anda">
            <x-input-error :messages="$errors->get('identifier')" class="mt-2 text-amber-700 text-sm" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password"
                          class="block mt-1 w-full rounded-lg border-gray-300 focus:border-amber-500 focus:ring-amber-500"
                          type="password"
                          name="password"
                          required />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-amber-700 text-sm" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input id="remember_me" type="checkbox"
                   class="rounded border-gray-300 text-amber-600 focus:ring-amber-500"
                   name="remember">
            <label for="remember_me" class="ms-2 text-sm text-gray-600">
                Remember me
            </label>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-between pt-4">
            @if (Route::has('password.request'))
                <a class="text-sm text-amber-600 hover:text-amber-700 hover:underline"
                   href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif

            <!-- BUTTON: gradasi kuning kalem -->
            <button
                class="bg-gradient-to-r from-amber-600 to-amber-400 hover:from-amber-700 hover:to-amber-500 text-white font-semibold px-4 py-2 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-amber-200">
                Masuk
            </button>
        </div>
    </form>

    <!-- Footer -->
    <div class="text-center text-xs text-gray-500 mt-6">
        &copy; {{ date('Y') }} Universitas Proklamasi 45 • Sistem Pengajuan Surat
    </div>

</x-guest-layout>
