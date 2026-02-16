<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Datacenter TI'))</title>

    <!-- Bootstrap 5 (Legacy Support) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- PSTI Theme -->
    <link href="{{ asset('css/psti-theme.css') }}" rel="stylesheet">

    <!-- Tailwind & Custom Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Bootstrap Override Fixes */
        .nav-link {
            display: block;
        }

        a {
            text-decoration: none;
        }
    </style>
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body class="h-full overflow-hidden flex text-gray-800">

    <!-- Sidebar Wrapper -->
    <div class="w-64 flex-shrink-0 h-full hidden md:block z-30 relative">
        @include('layouts.partials.sidebar')
    </div>

    <!-- Mobile Sidebar Backdrop & Toggle would go here -->

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full overflow-hidden bg-gray-50 relative">

        <!-- Top Navigation / Mobile Header -->
        <header
            class="bg-white/80 backdrop-blur-md border-b border-gray-200 h-16 flex items-center justify-between px-6 shadow-sm sticky top-0 z-20">
            <h1 class="text-xl font-bold text-gray-800 flex items-center">
                @yield('page-title', 'Dashboard')
            </h1>

            <div class="flex items-center space-x-4">
                @yield('page-actions')

                <!-- Simple Profile Dropdown Placeholder (Mobile) -->
                <div class="md:hidden">
                    <button class="text-gray-600 focus:outline-none">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Scrollable Content Area -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8 custom-scrollbar">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start"
                    role="alert">
                    <i class="fas fa-check-circle text-green-500 mt-1 mr-3"></i>
                    <div>
                        <p class="font-bold text-green-800">Success</p>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-start" role="alert">
                    <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                    <div>
                        <p class="font-bold text-red-800">Error</p>
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @yield('content')

            <footer class="mt-12 text-center text-sm text-gray-400 py-6 border-t border-gray-200">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </footer>
        </main>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Bootstrap tooltips (Legacy)
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });
    </script>
    @stack('scripts')
</body>

</html>