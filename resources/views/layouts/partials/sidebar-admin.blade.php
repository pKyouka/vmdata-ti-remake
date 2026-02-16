<div class="h-full sidebar-gradient text-white flex flex-col shadow-2xl">
    <div class="p-6 border-b border-white/10 flex items-center justify-center">
        <h4 class="text-xl font-bold tracking-tight flex items-center">
            <div class="p-2 rounded-lg mr-3 shadow-lg" style="background: #FFC107;">
                <i class="fas fa-server text-lg" style="color: #003366;"></i>
            </div>
            <span style="color: #FFC107;">PSTI</span> VM Rentals
        </h4>
    </div>

    <div class="flex-1 overflow-y-auto py-6 px-3 space-y-1">
        <!-- Dashboard -->
        <a class="nav-link-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
            href="{{ route('admin.dashboard') }}">
            <i class="fas fa-tachometer-alt w-6"></i>
            <span class="font-medium">Dashboard</span>
        </a>

        <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Management
        </div>

        <!-- VMs -->
        <a class="nav-link-custom {{ request()->routeIs('vms.*') ? 'active' : '' }}" href="{{ route('vms.index') }}">
            <i class="fas fa-desktop w-6"></i>
            <span class="font-medium">Virtual Machines</span>
        </a>

        <!-- Servers -->


        <!-- Rentals -->
        <!-- Rentals -->
        @php
            $pendingRentalsCount = \App\Models\VMRental::where('status', 'pending')->count();
        @endphp
        <a class="nav-link-custom {{ request()->routeIs('rentals.*') ? 'active' : '' }} flex justify-between items-center"
            href="{{ route('rentals.index') }}">
            <div class="flex items-center">
                <i class="fas fa-calendar-check w-6"></i>
                <span class="font-medium">Rentals</span>
            </div>
            @if($pendingRentalsCount > 0)
                <span
                    class="bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full ml-auto">{{ $pendingRentalsCount }}</span>
            @endif
        </a>

        <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            Analytics
        </div>

        <!-- Reports & Notifications -->
        @php $unread = optional(Auth::user())->unreadNotifications()->count() ?? 0; @endphp

        <div
            x-data="{ open: {{ (request()->routeIs('admin.reports.*') || request()->routeIs('admin.notifications.*')) ? 'true' : 'false' }} }">
            <button @click="open = !open" class="w-full nav-link-custom flex justify-between items-center group">
                <div class="flex items-center">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span class="font-medium">Reports</span>
                </div>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                    :class="{'transform rotate-180': open}"></i>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100" class="pl-4 mt-1 space-y-1">
                <a href="{{ route('admin.reports.index') }}"
                    class="flex items-center px-4 py-2 text-sm text-gray-300 rounded-lg hover:text-white hover:bg-white/5 transition-colors {{ request()->routeIs('admin.reports.*') ? 'bg-white/10 text-white' : '' }}">
                    <i class="fas fa-file-alt w-5 text-xs opacity-70"></i> Laporan
                </a>
                <a href="{{ route('admin.notifications.index') }}"
                    class="flex items-center justify-between px-4 py-2 text-sm text-gray-300 rounded-lg hover:text-white hover:bg-white/5 transition-colors {{ request()->routeIs('admin.notifications.*') ? 'bg-white/10 text-white' : '' }}">
                    <div class="flex items-center"><i class="fas fa-bell w-5 text-xs opacity-70"></i> Notifikasi</div>
                    @if($unread > 0)
                        <span class="px-2 py-0.5 text-xs font-bold bg-red-500 rounded-full">{{ $unread }}</span>
                    @endif
                </a>
            </div>
        </div>

        <div class="pt-4 pb-2 px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
            System
        </div>

        <!-- Settings -->
        <a class="nav-link-custom {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
            href="{{ route('admin.settings.index') }}">
            <i class="fas fa-cog w-6"></i>
            <span class="font-medium">Settings</span>
        </a>
    </div>

    <!-- User Profile Footer -->
    <div class="p-4 border-t border-white/10 bg-black/20">
        <div class="flex items-center">
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-md">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit"
                class="w-full py-2 px-4 bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white rounded-lg text-sm transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-sign-out-alt mr-2"></i> Log Out
            </button>
        </form>
    </div>
</div>