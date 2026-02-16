{{-- resources/views/layouts/user.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - {{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Inline sidebar styles to match screenshot: gradient, white text, wider left column */
        .app-sidebar {
            width: 220px;
            background: linear-gradient(180deg, #1e40af 0%, #5b247a 100%);
            color: #ffffff;
            min-height: 100vh;
        }

        .app-sidebar .brand h4 {
            color: #fff;
        }

        .app-sidebar .nav-link {
            color: rgba(255,255,255,0.95);
            padding: .7rem 1rem;
            border-radius: 6px;
            margin-bottom: .35rem;
            display: flex;
            align-items: center;
        }

        .app-sidebar .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: .6rem;
        }

        .app-sidebar .nav-link.active {
            background: rgba(255,255,255,0.12);
            box-shadow: inset 4px 0 0 rgba(255,255,255,0.12);
            font-weight: 600;
        }

        /* small footer area */
        .app-sidebar .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
        }

        /* ensure main content doesn't underlap */
        main.flex-1.p-6 { padding: 24px 36px; }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex">
        <!-- Sidebar -->
        <aside class="app-sidebar p-4 position-relative">
            @include('layouts.partials.sidebar')
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>
