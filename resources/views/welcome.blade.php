<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PSTI Infrastructure - Pusat Manajemen Resource Virtual</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #003366 0%, #001a33 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,193,7,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 193, 7, 0.1) 0%, transparent 70%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }

        .btn-hero {
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-hero:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Stats Section */
        .stats-section {
            margin-top: -80px;
            position: relative;
            z-index: 3;
        }

        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #6c757d;
            font-size: 1rem;
            font-weight: 500;
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: #f8f9fa;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        /* VMs Section */
        .vms-section {
            padding: 100px 0;
        }

        .vm-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .vm-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .vm-card-header {
            background: linear-gradient(135deg, #003366 0%, #001a33 100%);
            padding: 1.5rem;
            color: white;
        }

        .vm-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #003366 0%, #001a33 100%);
            padding: 100px 0;
            color: white;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            padding: 50px 0 30px;
        }

        .footer a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #FFC107;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out;
        }

        /* Utility Classes */
        .text-purple {
            color: #003366;
        }

        .bg-purple {
            background-color: #003366;
        }

        .text-gold {
            color: #FFC107;
        }

        .bg-gold {
            background-color: #FFC107;
        }

        .btn-psti {
            background: #FFC107;
            color: #003366;
            font-weight: 600;
        }

        .btn-psti:hover {
            background: #FFD700;
            color: #003366;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark position-absolute w-100" style="z-index: 10;">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="/">
                <i class="bi bi-server me-2"></i><span class="text-gold">PSTI</span> Infrastruktur
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="#vms">Katalog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    @guest
                        <li class="nav-item ms-3">
                            <a class="btn btn-outline-light px-4" href="{{ route('login') }}">Login</a>
                        </li>
                    @else
                        <li class="nav-item ms-3">
                            <a class="btn btn-light px-4"
                                href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}">
                                Dashboard
                            </a>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content animate-fade-in-up">
                    <h1 class="hero-title">Infrastruktur Virtualisasi & Pengembangan Sistem</h1>
                    <p class="hero-subtitle">
                        Platform manajemen resource komputasi terpusat untuk mendukung kegiatan riset, praktikum, dan
                        akselerasi pengembangan software di lingkungan PSTI.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-psti btn-hero">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Akses Sistem
                            </a>
                        @else
                            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                                class="btn btn-psti btn-hero">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        @endguest
                        <a href="#vms" class="btn btn-outline-light btn-hero">
                            <i class="bi bi-server me-2"></i>Lihat Resource
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center d-none d-lg-block">
                    <i class="bi bi-hdd-rack-fill text-white" style="font-size: 20rem; opacity: 0.2;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card text-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary mx-auto">
                            <i class="bi bi-server"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_vms'] }}</div>
                        <div class="stat-label">Total Unit VM</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card text-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success mx-auto">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-number">{{ $stats['available_vms'] }}</div>
                        <div class="stat-label">Resource Tersedia</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card text-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning mx-auto">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div class="stat-number">{{ $stats['active_rentals'] }}</div>
                        <div class="stat-label">Alokasi Aktif</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="stat-card text-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info mx-auto">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-number">{{ $stats['total_users'] }}</div>
                        <div class="stat-label">User Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->


    <!-- VMs Section -->
    <section class="vms-section" id="vms">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3">Katalog Resource</h2>
                <p class="text-muted fs-5">Daftar spesifikasi komputasi virtual yang siap digunakan untuk berbagai
                    skenario</p>
            </div>

            @if($recentVMs->count() > 0)
                <div class="row g-4">
                    @foreach($recentVMs as $vm)
                        <div class="col-md-4">
                            <div class="vm-card">
                                <div class="vm-card-header">
                                    <h5 class="mb-2 fw-bold">{{ $vm->name }}</h5>
                                    <span class="vm-badge bg-white text-primary">
                                        {{ $vm->category->name ?? 'Standard' }}
                                    </span>
                                </div>
                                <div class="p-4">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted"><i class="bi bi-cpu me-2"></i>CPU</span>
                                            <span class="fw-bold">{{ $vm->cpu }} vCPU</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted"><i class="bi bi-memory me-2"></i>RAM</span>
                                            <span class="fw-bold">{{ $vm->ram }} GB</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted"><i class="bi bi-hdd me-2"></i>Storage</span>
                                            <span class="fw-bold">{{ $vm->storage }} GB</span>
                                        </div>
                                        @if($vm->server)
                                            <div class="d-flex justify-content-between">
                                                <span class="text-muted"><i class="bi bi-server me-2"></i>Server</span>
                                                <span class="fw-bold">{{ $vm->server->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-grid">
                                        <a href="{{ route('login') }}" class="btn btn-primary">
                                            <i class="bi bi-arrow-right-circle me-2"></i>Ajukan Penggunaan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-hdd-network text-muted" style="font-size: 5rem;"></i>
                    <h4 class="text-muted mt-3">Tidak ada resource publik</h4>
                    <p class="text-muted">Semua unit sedang digunakan atau dalam maintenance</p>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="about">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Efisiensi Infrastruktur</h2>
            <p class="fs-5 mb-4">
                Optimalkan proses pengembangan dan testing sistem Anda dengan dukungan infrastruktur virtual yang stabil
                dan fleksibel.
            </p>
            <a href="{{ route('login') }}" class="btn btn-psti btn-lg btn-hero">
                <i class="bi bi-terminal me-2"></i>Masuk Konsol
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-server me-2"></i><span class="text-gold">PSTI</span> Infrastruktur
                    </h5>
                    <p class="text-white-50">
                        Sistem manajemen pusat data virtual untuk mendukung ekosistem teknologi informasi akademik.
                    </p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Quick Links</h5>
                    <ul class="list-unstyled">

                        <li class="mb-2"><a href="#vms">Katalog</a></li>
                        <li class="mb-2"><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold mb-3">Statistik</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2">{{ $stats['total_vms'] }} Total Unit</li>
                        <li class="mb-2">{{ $stats['total_servers'] }} Servers</li>
                        <li class="mb-2">{{ $stats['total_cpu'] }} vCPU Cores</li>
                        <li class="mb-2">{{ $stats['total_ram'] }} GB RAM</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="text-center text-white-50">
                <p class="mb-0">&copy; {{ date('Y') }} PSTI Infrastructure Labs - Universitas 'Aisyiyah Yogyakarta.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>