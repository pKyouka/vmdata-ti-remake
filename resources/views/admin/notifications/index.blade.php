@extends('layouts.app')

@section('title', 'Notifikasi - VM Rentals TI')
@section('page-title', 'Notifikasi')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <!-- Header Section -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="bg-primary bg-gradient rounded-3 p-3">
                                        <i class="bi bi-bell-fill fs-2 text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-1 fw-bold">Pusat Notifikasi</h4>
                                    <p class="text-muted mb-0">
                                        @if($notifications->isEmpty())
                                            Tidak ada notifikasi baru
                                        @else
                                            {{ $notifications->count() }} notifikasi menunggu
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if(!$notifications->isEmpty())
                                <div>
                                    <form action="{{ route('admin.notifications.clear') }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua notifikasi?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger">
                                            <i class="bi bi-trash3 me-2"></i>Hapus Semua
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notifications List -->
                @if($notifications->isEmpty())
                    <!-- Empty State -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light"
                                    style="width: 120px; height: 120px;">
                                    <i class="bi bi-bell-slash fs-1 text-muted"></i>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-2">Tidak Ada Notifikasi</h5>
                            <p class="text-muted mb-4">
                                Anda tidak memiliki notifikasi saat ini. Notifikasi baru akan muncul di sini.
                            </p>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                <i class="bi bi-house-door me-2"></i>Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Notifications Cards -->
                    <div class="notifications-container">
                        @foreach($notifications as $notification)
                            <div class="card border-0 shadow-sm mb-3 notification-card">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-start">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0">
                                            <div
                                                class="notification-icon {{ $loop->index % 4 == 0 ? 'bg-primary' : ($loop->index % 4 == 1 ? 'bg-success' : ($loop->index % 4 == 2 ? 'bg-warning' : 'bg-info')) }}">
                                                @php
                                                    $iconClass = 'bi-bell';
                                                    if (isset($notification->data['type'])) {
                                                        switch ($notification->data['type']) {
                                                            case 'rental':
                                                                $iconClass = 'bi-calendar-check';
                                                                break;
                                                            case 'vm':
                                                                $iconClass = 'bi-server';
                                                                break;
                                                            case 'user':
                                                                $iconClass = 'bi-person';
                                                                break;
                                                            case 'system':
                                                                $iconClass = 'bi-gear';
                                                                break;
                                                            default:
                                                                $iconClass = 'bi-bell';
                                                        }
                                                    }
                                                @endphp
                                                <i class="bi {{ $iconClass }}"></i>
                                            </div>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-grow-1 ms-3">
                                            <div class="d-flex align-items-start justify-content-between mb-2">
                                                <div>
                                                    <h6 class="fw-bold mb-1">
                                                        {{ $notification->data['title'] ?? 'Notifikasi' }}
                                                    </h6>
                                                    <p class="text-muted mb-0">
                                                        {{ $notification->data['message'] ?? 'Tidak ada pesan' }}
                                                    </p>
                                                </div>
                                                @if(!$notification->read_at)
                                                    <span class="badge bg-danger rounded-pill">Baru</span>
                                                @endif
                                            </div>

                                            <!-- Meta Info -->
                                            <div class="d-flex align-items-center text-muted small mb-3">
                                                <i class="bi bi-clock me-1"></i>
                                                <span>{{ $notification->created_at->diffForHumans() }}</span>
                                                <span class="mx-2">•</span>
                                                <i class="bi bi-calendar3 me-1"></i>
                                            <span>{{ formatDateTime($notification->created_at) }}</span>
                                            </div>

                                            <!-- Actions -->
                                            <div class="d-flex gap-2">
                                                @if(isset($notification->data['url']))
                                                    <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye me-1"></i>Lihat Detail
                                                    </a>
                                                @endif

                                                <form action="{{ route('admin.notifications.read', $notification->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus notifikasi ini?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="bi bi-trash3 me-1"></i>Hapus
                                                    </button>
                                                </form>

                                                @if(!$notification->read_at)
                                                    <button type="button" class="btn btn-sm btn-outline-secondary">
                                                        <i class="bi bi-check2 me-1"></i>Tandai Dibaca
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination if needed -->
                    @if(method_exists($notifications, 'links'))
                        <div class="mt-4">
                            {{ $notifications->links() }}
                        </div>
                    @endif
                @endif

                <!-- Info Card -->
                <div class="card border-0 shadow-sm mt-4 bg-light">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-info-circle-fill text-primary fs-4 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Tentang Notifikasi</h6>
                                <p class="text-muted small mb-0">
                                    Notifikasi akan muncul ketika ada aktivitas penting seperti penyewaan baru,
                                    perubahan status VM, atau update sistem. Anda dapat menghapus notifikasi yang
                                    sudah tidak diperlukan atau menghapus semua notifikasi sekaligus.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .notification-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent !important;
        }

        .notification-card:hover {
            transform: translateX(5px);
            box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
            border-left-color: var(--bs-primary) !important;
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .notifications-container {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn {
            transition: all 0.3s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }
    </style>

    <script>
        // Auto-hide success messages
        document.addEventListener('DOMContentLoaded', function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
        });

        // Smooth scroll to top when deleting notification
        document.querySelectorAll('form[action*="notifications"]').forEach(form => {
            form.addEventListener('submit', function () {
                setTimeout(() => {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }, 100);
            });
        });
    </script>
@endsection