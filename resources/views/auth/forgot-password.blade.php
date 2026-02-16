<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center gradient-psti">
        <div class="max-w-md w-full mx-4">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="bg-white p-4 rounded-full inline-block shadow-lg mb-4 icon-bg-psti">
                    <svg class="w-8 h-8 text-psti-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white"><span class="text-psti-gold">PSTI</span> VM Rentals</h2>
                <p class="text-gray-200 mt-2">Reset Password</p>
            </div>

            <!-- Card -->
            <div class="bg-white shadow-xl rounded-lg p-8">
                <div class="mb-4 text-sm text-gray-600">
                    {{ __('Lupa password Anda? Tidak masalah. Beritahu kami alamat email Anda dan kami akan mengirimkan tautan reset password.') }}
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')"
                            class="mb-2 block text-sm font-medium text-gray-700" />
                        <x-text-input id="email"
                            class="block mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4 text-center">
                        <x-primary-button
                            class="w-full justify-center btn-psti-navy py-3 px-4 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            {{ __('Kirim Link Reset Password') }}
                        </x-primary-button>
                    </div>

                    <div class="mt-4 text-center">
                        <a href="{{ route('login') }}" class="text-sm link-psti-navy font-medium">
                            Kembali ke Login
                        </a>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-sm">
                <p class="text-white opacity-80">&copy; {{ date('Y') }} PSTI VM Rentals - Universitas 'Aisyiyah
                    Yogyakarta. All rights reserved.</p>
            </div>
        </div>
    </div>
</x-guest-layout>