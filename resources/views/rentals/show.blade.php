@extends('layouts.app')

@section('title', 'Detail Rental')
@section('page-title', 'Detail Rental')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                {{-- Header --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">Detail Rental #{{ $rental->id }}</h2>
                        <p class="text-muted mb-0">Informasi lengkap rental VM</p>
                    </div>
                    <div>
                        @php
                            $st = strtolower($rental->status ?? 'active');
                            $badgeClass = match ($st) {
                                'active' => 'bg-success',
                                'cancelled' => 'bg-secondary',
                                'expired' => 'bg-danger',
                                'completed' => 'bg-info',
                                default => 'bg-warning'
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} px-4 py-2 fs-6">
                            {{ ucfirst($rental->status ?? 'Active') }}
                        </span>
                    </div>
                </div>

                <div class="row g-4">
                    {{-- User Info Card --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-gradient text-white border-0"
                                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                <h5 class="mb-0">
                                    <i class="fas fa-user me-2"></i>Informasi User
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    @if($rental->user->avatar)
                                        <img src="{{ asset('storage/' . $rental->user->avatar) }}" alt="avatar"
                                            class="rounded-circle me-3" width="80" height="80"
                                            style="border: 3px solid #667eea;">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                            style="width: 80px; height: 80px; font-size: 32px; font-weight: 600; border: 3px solid #667eea;">
                                            {{ substr($rental->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="mb-1">{{ $rental->user->name }}</h4>
                                        <p class="text-muted mb-0">
                                            <i class="fas fa-envelope me-1"></i>{{ $rental->user->email }}
                                        </p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <small class="text-muted d-block">Role</small>
                                        <span class="badge bg-primary">{{ ucfirst($rental->user->role ?? 'User') }}</span>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted d-block">User ID</small>
                                        <strong>#{{ $rental->user->id }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VM Info Card --}}
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-gradient text-white border-0"
                                style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                <h5 class="mb-0">
                                    <i class="fas fa-server me-2"></i>Informasi Virtual Machine
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <h4 class="mb-3">{{ $rental->vm->name }}</h4>
                                <div class="row g-3">
                                    <div class="col-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="fas fa-memory fa-2x text-primary mb-2"></i>
                                            <div class="fw-bold">{{ $rental->vm->ram }} GB</div>
                                            <small class="text-muted">RAM</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="fas fa-microchip fa-2x text-success mb-2"></i>
                                            <div class="fw-bold">{{ $rental->vm->cpu }} vCPU</div>
                                            <small class="text-muted">CPU</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-3 bg-light rounded">
                                            <i class="fas fa-hdd fa-2x text-warning mb-2"></i>
                                            <div class="fw-bold">{{ $rental->vm->storage }} GB</div>
                                            <small class="text-muted">Storage</small>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">VM ID</small>
                                    <strong>#{{ $rental->vm->id }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Rental Details Card --}}
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-gradient text-white border-0"
                                style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-alt me-2"></i>Detail Rental
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded">
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-calendar-plus me-1"></i>Tanggal Mulai
                                            </small>
                                            <h5 class="mb-0">
                                                {{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y') }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded">
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-calendar-check me-1"></i>Tanggal Selesai
                                            </small>
                                            <h5 class="mb-0">{{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded">
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-hourglass-half me-1"></i>Durasi
                                            </small>
                                            <h5 class="mb-0">
                                                {{ \Carbon\Carbon::parse($rental->start_date)->diffInDays($rental->end_date) }}
                                                Hari
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="p-3 bg-light rounded">
                                            <small class="text-muted d-block mb-1">
                                                <i class="fas fa-user-shield me-1"></i>Penanggung Jawab
                                            </small>
                                            <h6 class="mb-0">{{ $rental->admin->name ?? '-' }}</h6>
                                        </div>
                                    </div>
                                </div>

                                {{-- Timeline --}}
                                <div class="mt-4">
                                    <h6 class="mb-3">Timeline Rental</h6>
                                    <div class="timeline">
                                        @php
                                            $start = \Carbon\Carbon::parse($rental->start_date);
                                            $end = \Carbon\Carbon::parse($rental->end_date);
                                            $now = \Carbon\Carbon::now();
                                            $total = $start->diffInDays($end);
                                            $elapsed = $start->diffInDays($now);
                                            $progress = $total > 0 ? min(100, max(0, ($elapsed / $total) * 100)) : 0;
                                        @endphp
                                        <div class="progress" style="height: 30px;">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                role="progressbar" style="width: {{ $progress }}%">
                                                {{ round($progress) }}%
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <small class="text-muted">Mulai: {{ $start->format('d/m/Y') }}</small>
                                            <small class="text-muted">Sekarang: {{ $now->format('d/m/Y') }}</small>
                                            <small class="text-muted">Selesai: {{ $end->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- VM Credentials Card - Always shown --}}
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-gradient text-white border-0"
                                style="background: linear-gradient(135deg, #fbc2eb 0%, #a6c1ee 100%);">
                                <h5 class="mb-0">
                                    <i class="fas fa-key me-2"></i>Kredensial & Akses VM
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="alert alert-info border-0 bg-opacity-25 mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Informasi:</strong> Gunakan kredensial berikut untuk mengakses Virtual Machine.
                                </div>
                                
                                <div class="row g-4">
                                    {{-- IP Address - Always shown --}}
                                    <div class="col-md-12">
                                        <div class="p-3 bg-light rounded border border-success">
                                            <small class="text-muted d-block mb-2">
                                                <i class="fas fa-network-wired me-1 text-success"></i>IP Address VM
                                            </small>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h5 class="mb-0 font-monospace text-success">
                                                    {{ $rental->vm_ip_address ?: 'Belum diatur' }}
                                                </h5>
                                                @if($rental->vm_ip_address)
                                                    <button class="btn btn-sm btn-outline-success copy-btn" 
                                                        data-clipboard-text="{{ $rental->vm_ip_address }}"
                                                        title="Copy IP address">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($rental->vm_username)
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded">
                                                <small class="text-muted d-block mb-2">
                                                    <i class="fas fa-user me-1"></i>Username
                                                </small>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h5 class="mb-0 font-monospace">{{ $rental->vm_username }}</h5>
                                                    <button class="btn btn-sm btn-outline-primary copy-btn" 
                                                        data-clipboard-text="{{ $rental->vm_username }}"
                                                        title="Copy username">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($rental->vm_password)
                                        <div class="col-md-6">
                                            <div class="p-3 bg-light rounded">
                                                <small class="text-muted d-block mb-2">
                                                    <i class="fas fa-lock me-1"></i>Password
                                                </small>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h5 class="mb-0 font-monospace" id="passwordText">••••••••</h5>
                                                    <div class="btn-group" role="group">
                                                        <button class="btn btn-sm btn-outline-secondary" id="togglePasswordView"
                                                            data-password="{{ $rental->vm_password }}"
                                                            title="Show/hide password">
                                                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-outline-primary copy-btn" 
                                                            data-clipboard-text="{{ $rental->vm_password }}"
                                                            title="Copy password">
                                                            <i class="fas fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="d-flex gap-2 justify-content-end mt-4">
                    <a href="{{ route('rentals.index') }}" class="btn btn-lg btn-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    @if(auth()->user() && auth()->user()->isAdmin())
                        <a href="{{ route('rentals.edit', $rental->id) }}" class="btn btn-lg btn-warning px-4">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus rental ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-lg btn-danger px-4">
                                <i class="fas fa-trash me-2"></i>Hapus
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .card {
                border-radius: 12px;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            .btn-lg {
                border-radius: 8px;
                font-weight: 500;
                transition: all 0.3s ease;
            }

            .btn-lg:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }

            .timeline {
                position: relative;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Toggle password visibility
                const togglePasswordView = document.getElementById('togglePasswordView');
                const passwordText = document.getElementById('passwordText');
                const togglePasswordIcon = document.getElementById('togglePasswordIcon');

                if (togglePasswordView) {
                    togglePasswordView.addEventListener('click', function () {
                        const password = this.getAttribute('data-password');
                        const isHidden = passwordText.textContent === '••••••••';

                        if (isHidden) {
                            passwordText.textContent = password;
                            togglePasswordIcon.classList.remove('fa-eye');
                            togglePasswordIcon.classList.add('fa-eye-slash');
                        } else {
                            passwordText.textContent = '••••••••';
                            togglePasswordIcon.classList.remove('fa-eye-slash');
                            togglePasswordIcon.classList.add('fa-eye');
                        }
                    });
                }

                // Copy to clipboard functionality
                const copyButtons = document.querySelectorAll('.copy-btn');
                copyButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const text = this.getAttribute('data-clipboard-text');
                        navigator.clipboard.writeText(text).then(() => {
                            const originalIcon = this.querySelector('i');
                            const originalClass = originalIcon.className;

                            // Change icon to check
                            originalIcon.className = 'fas fa-check';
                            this.classList.remove('btn-outline-primary');
                            this.classList.add('btn-success');

                            // Reset after 2 seconds
                            setTimeout(() => {
                                originalIcon.className = originalClass;
                                this.classList.remove('btn-success');
                                this.classList.add('btn-outline-primary');
                            }, 2000);
                        }).catch(err => {
                            console.error('Failed to copy:', err);
                        });
                    });
                });
            });
        </script>
    @endpush
@endsection