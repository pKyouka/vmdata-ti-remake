@extends('layouts.app')

@section('title', 'Laporan - VM Rentals TI')
@section('page-title', 'Laporan & Analitik')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Section -->
                <div class="mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="fw-bold mb-2">📊 Laporan & Analitik</h2>
                            <p class="text-muted mb-0">Akses berbagai laporan sistem untuk analisis dan monitoring</p>
                        </div>
                        <div>
                            <span class="badge badge-psti-navy fs-6 px-3 py-2">
                                <i class="bi bi-calendar-event me-2"></i>{{ formatDate(now()) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Report Cards Grid -->
                <div class="row g-4">
                    <!-- VM Report Card -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 report-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="icon-box bg-psti-navy bg-gradient">
                                            <i class="bi bi-server fs-3"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="card-title fw-bold mb-1">Laporan Virtual Machines</h5>
                                        <p class="text-muted small mb-0">Status dan statistik VM</p>
                                    </div>
                                </div>

                                <p class="text-muted mb-4">
                                    Lihat daftar lengkap virtual machines, status operasional, spesifikasi,
                                    dan informasi detail lainnya.
                                </p>

                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="text-center flex-fill">
                                        <div class="fs-4 fw-bold text-psti-navy">{{ \App\Models\VM::count() }}</div>
                                        <small class="text-muted">Total VMs</small>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-center flex-fill">
                                        <div class="fs-4 fw-bold text-success">
                                            {{ \App\Models\VM::where('status', 'available')->count() }}
                                        </div>
                                        <small class="text-muted">Available</small>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-center flex-fill">
                                        <div class="fs-4 fw-bold text-warning">
                                            {{ \App\Models\VM::where('status', 'rented')->count() }}
                                        </div>
                                        <small class="text-muted">Rented</small>
                                    </div>
                                </div>

                                <a href="{{ route('admin.reports.vm') }}" class="btn btn-psti-navy w-100 btn-hover">
                                    <i class="bi bi-file-earmark-text me-2"></i>Lihat Laporan
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Rental Report Card -->
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100 report-card">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="icon-box bg-success bg-gradient">
                                            <i class="bi bi-calendar-check fs-3"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="card-title fw-bold mb-1">Laporan Penyewaan</h5>
                                        <p class="text-muted small mb-0">Riwayat dan status rental</p>
                                    </div>
                                </div>

                                <p class="text-muted mb-4">
                                    Analisis data penyewaan VM, termasuk durasi, biaya, dan informasi
                                    pelanggan yang lengkap.
                                </p>

                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <div class="text-center flex-fill">
                                        <div class="fs-4 fw-bold text-success">{{ \App\Models\Rental::count() }}</div>
                                        <small class="text-muted">Total Rentals</small>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-center flex-fill">
                                        <div class="fs-4 fw-bold text-info">
                                            {{ \App\Models\Rental::where('status', 'active')->count() }}
                                        </div>
                                        <small class="text-muted">Active</small>
                                    </div>
                                    <div class="vr"></div>
                                    <div class="text-center flex-fill">
                                        <div class="fs-4 fw-bold text-secondary">
                                            {{ \App\Models\Rental::where('status', 'completed')->count() }}
                                        </div>
                                        <small class="text-muted">Completed</small>
                                    </div>
                                </div>

                                <a href="{{ route('admin.reports.rental') }}" class="btn btn-success w-100 btn-hover">
                                    <i class="bi bi-file-earmark-text me-2"></i>Lihat Laporan
                                </a>
                            </div>

                        </div>
                    </div>


                </div>

                <!-- Quick Stats Section -->
                <div class="row g-4 mt-2">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-speedometer2 me-2 text-psti-navy"></i>Statistik Cepat
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <div class="stat-card">
                                            <div class="stat-icon bg-psti-navy bg-opacity-10">
                                                <i class="bi bi-people-fill text-psti-navy"></i>
                                            </div>
                                            <div class="stat-content">
                                                <h3 class="fw-bold mb-1">{{ \App\Models\User::count() }}</h3>
                                                <p class="text-muted mb-0">Total Users</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-card">
                                            <div class="stat-icon bg-success bg-opacity-10">
                                                <i class="bi bi-hdd-rack-fill text-success"></i>
                                            </div>
                                            <div class="stat-content">
                                                <h3 class="fw-bold mb-1">{{ \App\Models\Server::count() }}</h3>
                                                <p class="text-muted mb-0">Total Servers</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-card">
                                            <div class="stat-icon bg-warning bg-opacity-10">
                                                <i class="bi bi-cpu-fill text-warning"></i>
                                            </div>
                                            <div class="stat-content">
                                                <h3 class="fw-bold mb-1">{{ \App\Models\VM::sum('cpu') }}</h3>
                                                <p class="text-muted mb-0">Total vCPU</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="stat-card">
                                            <div class="stat-icon bg-info bg-opacity-10">
                                                <i class="bi bi-memory text-info"></i>
                                            </div>
                                            <div class="stat-content">
                                                <h3 class="fw-bold mb-1">{{ \App\Models\VM::sum('ram') }} GB</h3>
                                                <p class="text-muted mb-0">Total RAM</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .report-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .btn-hover {
            transition: all 0.3s ease;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .vr {
            opacity: 0.2;
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border-radius: 8px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-content h3 {
            font-size: 1.75rem;
            color: #2c3e50;
        }

        .stat-content p {
            font-size: 0.875rem;
        }
    </style>
@endsection