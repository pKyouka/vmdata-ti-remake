@extends('layouts.app')

@section('title', 'Edit Rental')
@section('page-title', 'Edit Rental')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient text-white border-0 py-3"
                        style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                        <h4 class="mb-0">
                            <i class="fas fa-edit me-2"></i>Edit Rental #{{ $rental->id }}
                        </h4>
                    </div>
                    <div class="card-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Terdapat kesalahan!</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('rentals.update', $rental->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label fw-semibold">
                                        <i class="fas fa-user text-primary me-1"></i>User
                                    </label>
                                    <select name="user_id" id="user_id" class="form-select form-select-lg" disabled>
                                        <option value="{{ $rental->user->id }}">
                                            {{ $rental->user->name }} ({{ $rental->user->email }})
                                        </option>
                                    </select>
                                    <small class="text-muted">
                                        <i class="fas fa-lock me-1"></i>User tidak dapat diubah
                                    </small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="vm_id" class="form-label fw-semibold">
                                        <i class="fas fa-server text-info me-1"></i>Virtual Machine
                                    </label>
                                    <select name="vm_id" id="vm_id" class="form-select form-select-lg" disabled>
                                        <option value="{{ $rental->vm->id }}">
                                            {{ $rental->vm->name }} - {{ $rental->vm->ram }}GB RAM, {{ $rental->vm->cpu }}
                                            vCPU
                                        </option>
                                    </select>
                                    <small class="text-muted">
                                        <i class="fas fa-lock me-1"></i>VM tidak dapat diubah
                                    </small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-alt text-success me-1"></i>Tanggal Mulai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="start_date"
                                        class="form-control form-control-lg"
                                        value="{{ old('start_date', $rental->start_date) }}" required>
                                    @error('start_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-check text-warning me-1"></i>Tanggal Selesai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="end_date" id="end_date" class="form-control form-control-lg"
                                        value="{{ old('end_date', $rental->end_date) }}" required>
                                    @error('end_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label fw-semibold">
                                        <i class="fas fa-info-circle text-primary me-1"></i>Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" id="status" class="form-select form-select-lg" required>
                                        <option value="active" {{ old('status', $rental->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="pending" {{ old('status', $rental->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="expired" {{ old('status', $rental->status) == 'expired' ? 'selected' : '' }}>Expired</option>
                                        <option value="completed" {{ old('status', $rental->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $rental->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="admin_id" class="form-label fw-semibold">
                                        <i class="fas fa-user-shield text-danger me-1"></i>Penanggung Jawab
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="admin_id" id="admin_id" class="form-select form-select-lg" required>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ old('admin_id', $rental->admin_id) == $admin->id ? 'selected' : '' }}>
                                                {{ $admin->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('admin_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- VM Credentials Section --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card bg-light border-0">
                                        <div class="card-body">
                                            <h6 class="mb-3">
                                                <i class="fas fa-key text-warning me-2"></i>Kredensial VM
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="vm_username" class="form-label fw-semibold">
                                                        <i class="fas fa-user text-primary me-1"></i>Username VM
                                                    </label>
                                                    <input type="text" name="vm_username" id="vm_username"
                                                        class="form-control form-control-lg"
                                                        value="{{ old('vm_username', $rental->vm_username) }}"
                                                        placeholder="Username untuk akses VM">
                                                    @error('vm_username')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Username yang akan diberikan ke user untuk
                                                        login ke VM</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="vm_password" class="form-label fw-semibold">
                                                        <i class="fas fa-lock text-danger me-1"></i>Password VM
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="password" name="vm_password" id="vm_password"
                                                            class="form-control form-control-lg"
                                                            value="{{ old('vm_password', $rental->vm_password) }}"
                                                            placeholder="Password untuk akses VM">
                                                        <button class="btn btn-outline-secondary" type="button"
                                                            id="togglePassword">
                                                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                                        </button>
                                                    </div>
                                                    @error('vm_password')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Kosongkan jika tidak ingin mengubah
                                                        password</small>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="vm_ip_address" class="form-label fw-semibold">
                                                        <i class="fas fa-network-wired text-success me-1"></i>IP Address VM
                                                    </label>
                                                    <input type="text" name="vm_ip_address" id="vm_ip_address"
                                                        class="form-control form-control-lg"
                                                        value="{{ old('vm_ip_address', $rental->vm_ip_address) }}"
                                                        placeholder="Contoh: 192.168.1.100 atau 10.0.0.5">
                                                    @error('vm_ip_address')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">IP Address yang akan digunakan user untuk
                                                        mengakses VM</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning border-0 bg-opacity-25">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Perhatian:</strong> Status akan otomatis disesuaikan berdasarkan tanggal yang Anda
                                pilih.
                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <a href="{{ route('rentals.index') }}" class="btn btn-lg btn-secondary px-4">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-lg btn-primary px-4">
                                    <i class="fas fa-save me-2"></i>Update Rental
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .form-select-lg,
            .form-control-lg {
                border-radius: 8px;
                border: 2px solid #e0e0e0;
                transition: all 0.3s ease;
            }

            .form-select-lg:focus,
            .form-control-lg:focus {
                border-color: #f093fb;
                box-shadow: 0 0 0 0.2rem rgba(240, 147, 251, 0.25);
            }

            .form-select-lg:disabled,
            .form-control-lg:disabled {
                background-color: #f8f9fa;
                cursor: not-allowed;
            }

            .card {
                border-radius: 12px;
                overflow: hidden;
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
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const togglePassword = document.getElementById('togglePassword');
                const passwordInput = document.getElementById('vm_password');
                const toggleIcon = document.getElementById('togglePasswordIcon');

                if (togglePassword) {
                    togglePassword.addEventListener('click', function () {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);

                        if (type === 'text') {
                            toggleIcon.classList.remove('fa-eye');
                            toggleIcon.classList.add('fa-eye-slash');
                        } else {
                            toggleIcon.classList.remove('fa-eye-slash');
                            toggleIcon.classList.add('fa-eye');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection