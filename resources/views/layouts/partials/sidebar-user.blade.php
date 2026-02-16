<div class="h-full sidebar-gradient text-white flex flex-col shadow-2xl">
    <!-- Brand Header -->
    <div class="p-6 border-b border-white/10 flex items-center justify-center">
        <h4 class="text-xl font-bold tracking-tight flex items-center">
            <div class="p-2 rounded-lg mr-3 shadow-lg" style="background: #FFC107;">
                <i class="fas fa-server text-lg" style="color: #003366;"></i>
            </div>
            <span style="color: #FFC107;">PSTI</span> VM Rentals
        </h4>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        <!-- Dashboard -->
        <a class="nav-link-custom {{ request()->routeIs('user.dashboard') ? 'active' : '' }}"
            href="{{ route('user.dashboard') }}">
            <i class="fas fa-home w-6"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <!-- Account Section -->
        <div class="pt-6 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Akun Saya
        </div>

        <a class="nav-link-custom {{ request()->routeIs('user.profile') ? 'active' : '' }}"
            href="{{ route('user.profile') }}">
            <i class="fas fa-user-circle w-6"></i>
            <span class="font-medium">Profil</span>
        </a>

        <!-- Services Section -->
        @if(Route::has('vmrentals.index'))
            <div class="pt-6 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Layanan Sewa
            </div>

            <a class="nav-link-custom {{ request()->routeIs('vmrentals.index') || request()->routeIs('vmrentals.show') ? 'active' : '' }}"
                href="{{ route('vmrentals.index') }}">
                <i class="fas fa-list-alt w-6"></i>
                <span class="font-medium">Daftar Sewa Saya</span>
            </a>

            <a class="nav-link-custom {{ request()->routeIs('vmrentals.create') ? 'active' : '' }}"
                href="{{ route('vmrentals.create') }}">
                <i class="fas fa-plus-circle w-6"></i>
                <span class="font-medium">Buat Permintaan</span>
            </a>
        @endif
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-white/10 bg-black/20">
        <div class="flex items-center mb-4">
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg shadow-md border-2 border-white/20">
                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
            </div>
            <div class="ml-3 overflow-hidden">
                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">Pengguna</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full py-2 px-4 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-lg text-sm transition-all duration-200 flex items-center justify-center font-medium group">
                <i class="fas fa-sign-out-alt mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
                Keluar
            </button>
        </form>
    </div>
</div>