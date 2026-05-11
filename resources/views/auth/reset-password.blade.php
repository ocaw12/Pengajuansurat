<x-guest-layout>
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-100 text-amber-600 rounded-full mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Atur Ulang Kata Sandi</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Silakan masukkan kata sandi baru Anda di bawah ini.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div>
            <x-input-label for="email" :value="__('Email')" class="font-semibold" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="bi bi-envelope"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-10 bg-gray-50 border-gray-300 text-gray-500 rounded-xl shadow-sm cursor-not-allowed" 
                    type="email" 
                    name="email" 
                    :value="old('email', request()->email)" 
                    required 
                    readonly 
                    autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Kata Sandi Baru')" class="font-semibold" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="bi bi-lock"></i>
                </div>
                <x-text-input id="password" class="block w-full pl-10 border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm" 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="••••••••"
                    autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="font-semibold" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="bi bi-shield-lock"></i>
                </div>
                <x-text-input id="password_confirmation" class="block w-full pl-10 border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm"
                    type="password"
                    name="password_confirmation" 
                    required 
                    placeholder="••••••••"
                    autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="pt-2 flex flex-col gap-4">
            <x-primary-button class="w-full justify-center py-3 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-100 transition-all duration-200 uppercase tracking-widest">
                {{ __('Perbarui Kata Sandi') }}
            </x-primary-button>

            <div class="text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-amber-600 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Halaman Login
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>