<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center gradient-psti">
        <div class="max-w-md w-full mx-4">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="bg-white p-4 rounded-full inline-block shadow-lg mb-4 icon-bg-psti">
                    <svg class="w-8 h-8 text-psti-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-white"><span class="text-psti-gold">PSTI</span> VM Rentals</h2>
                <p class="text-gray-200 mt-2">Daftar untuk mulai menyewa VM</p>
            </div>

            <!-- Register Card -->
            <div class="bg-white shadow-xl rounded-lg p-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="mb-5">
                        <x-input-label for="name" :value="__('Nama Lengkap')"
                            class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            {{ __('Nama Lengkap') }}
                        </x-input-label>
                        <x-text-input id="name"
                            class="block mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                            placeholder="Masukkan nama lengkap Anda" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email Address -->
                    <div class="mb-5">
                        <x-input-label for="email" :value="__('Email')"
                            class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            {{ __('Email Address') }}
                        </x-input-label>
                        <x-text-input id="email"
                            class="block mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                            type="email" name="email" :value="old('email')" required autocomplete="username"
                            placeholder="Masukkan email aktif Anda" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <x-input-label for="password" :value="__('Password')"
                            class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m0 0v2m0-2h2m-2 0H10m2-12a4 4 0 00-4 4v1m8-5a4 4 0 00-4-4v0a4 4 0 00-4 4v1h8z">
                                </path>
                            </svg>
                            {{ __('Password') }}
                        </x-input-label>
                        <div class="relative">
                            <x-text-input id="password"
                                class="block mt-1 w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                type="password" name="password" required autocomplete="new-password"
                                placeholder="Buat password yang aman" />
                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                onclick="togglePasswordVisibility('password', 'eyeIconShowPass', 'eyeIconHidePass')">
                                <svg id="eyeIconShowPass" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg id="eyeIconHidePass" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m6-6l-6 6m0 0l6 6m-6-6l-6 6">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')"
                            class="flex items-center text-sm font-medium text-gray-700 mb-2">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ __('Konfirmasi Password') }}
                        </x-input-label>
                        <div class="relative">
                            <x-text-input id="password_confirmation"
                                class="block mt-1 w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200"
                                type="password" name="password_confirmation" required autocomplete="new-password"
                                placeholder="Ulangi password Anda" />
                            <button type="button"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none"
                                onclick="togglePasswordVisibility('password_confirmation', 'eyeIconShowConf', 'eyeIconHideConf')">
                                <svg id="eyeIconShowConf" class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                <svg id="eyeIconHideConf" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L12 12m6-6l-6 6m0 0l6 6m-6-6l-6 6">
                                    </path>
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-8">
                        <a class="text-sm text-gray-600 hover:text-psti-navy rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200"
                            href="{{ route('login') }}">
                            {{ __('Sudah punya akun?') }}
                        </a>

                        <x-primary-button
                            class="ml-4 btn-psti-navy font-bold py-3 px-6 rounded-lg transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            {{ __('Daftar Sekarang') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="text-center mt-8 text-gray-500 text-sm">
                <p class="text-white opacity-80">&copy; {{ date('Y') }} PSTI VM Rentals - Universitas 'Aisyiyah
                    Yogyakarta. All rights reserved.</p>
            </div>
        </div>
    </div>

    <!-- JavaScript for Password Toggle (Reusable) -->
    <script>
        function togglePasswordVisibility(inputId, showIconId, hideIconId) {
            const passwordInput = document.getElementById(inputId);
            const eyeIconShow = document.getElementById(showIconId);
            const eyeIconHide = document.getElementById(hideIconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIconShow.classList.add('hidden');
                eyeIconHide.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIconShow.classList.remove('hidden');
                eyeIconHide.classList.add('hidden');
            }
        }
    </script>
</x-guest-layout>