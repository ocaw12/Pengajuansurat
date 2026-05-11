<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-100 text-amber-600 rounded-full mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </div>
        
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Lupa Kata Sandi?</h2>
        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 px-4">
            {{ __('Jangan khawatir! Cukup masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.') }}
        </p>
    </div>

    @if (session('status'))
        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 text-sm rounded shadow-sm">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <x-auth-session-status :status="session('status')" />
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" class="font-semibold text-gray-700" />
            <div class="relative mt-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="bi bi-envelope"></i>
                </div>
                <x-text-input id="email" class="block w-full pl-10 border-gray-300 focus:border-amber-500 focus:ring-amber-500 rounded-xl shadow-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
        </div>

        <div class="flex flex-col gap-4">
            <x-primary-button class="w-full justify-center py-3 bg-amber-500 hover:bg-amber-600 active:bg-amber-700 text-white font-bold rounded-xl shadow-lg shadow-amber-200 transition-all duration-200 uppercase tracking-widest">
                {{ __('Kirim Link Reset Password') }}
            </x-primary-button>
            
            <div class="text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium text-amber-600 hover:text-amber-500 transition-colors">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login
                </a>
            </div>
        </div>
    </form>
</x-guest-layout>