@extends('layouts.app')

@section('title', 'Tambah Rental')
@section('page-title', 'Tambah Rental')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient text-white border-0 py-3"
                        style="background: linear-gradient(135deg, #003366 0%, #001a33 100%);">
                        <h4 class="mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Tambah Rental Baru
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

                        <form action="{{ route('rentals.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="user_id" class="form-label fw-semibold">
                                        <i class="fas fa-user text-primary me-1"></i>User
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="user_id" id="user_id" class="form-select form-select-lg" required>
                                        <option value="">-- Pilih User --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }} ({{ $user->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="vm_id" class="form-label fw-semibold">
                                        <i class="fas fa-server text-info me-1"></i>Virtual Machine
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="vm_id" id="vm_id" class="form-select form-select-lg" required>
                                        <option value="">-- Pilih VM --</option>
                                        @foreach($vms as $vm)
                                            <option value="{{ $vm->id }}" {{ old('vm_id') == $vm->id ? 'selected' : '' }}>
                                                {{ $vm->name }} - {{ $vm->ram }}GB RAM, {{ $vm->cpu }} vCPU
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vm_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-alt text-success me-1"></i>Tanggal Mulai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="start_date" id="start_date"
                                        class="form-control form-control-lg datepicker" 
                                        value="{{ old('start_date') }}" 
                                        placeholder="dd/mm/yyyy"
                                        required>
                                    @error('start_date')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label fw-semibold">
                                        <i class="fas fa-calendar-check text-warning me-1"></i>Tanggal Selesai
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="end_date" id="end_date" 
                                        class="form-control form-control-lg datepicker"
                                        value="{{ old('end_date') }}" 
                                        placeholder="dd/mm/yyyy"
                                        required>
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
                                        <option value="">-- Pilih Status --</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                            <i class="fas fa-check-circle"></i> Active
                                        </option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                            Completed
                                        </option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
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
                                        <option value="">-- Pilih Admin --</option>
                                        @foreach($admins as $admin)
                                            <option value="{{ $admin->id }}" {{ old('admin_id') == $admin->id ? 'selected' : '' }}>
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
                                                        class="form-control form-control-lg" value="{{ old('vm_username') }}"
                                                        placeholder="Username untuk akses VM">
                                                    @error('vm_username')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Username yang akan diberikan ke user untuk login ke VM</small>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="vm_password" class="form-label fw-semibold">
                                                        <i class="fas fa-lock text-danger me-1"></i>Password VM
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="password" name="vm_password" id="vm_password"
                                                            class="form-control form-control-lg" value="{{ old('vm_password') }}"
                                                            placeholder="Password untuk akses VM">
                                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                                        </button>
                                                    </div>
                                                    @error('vm_password')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">Password yang akan diberikan ke user untuk login ke VM</small>
                                                </div>

                                                <div class="col-md-12 mb-3">
                                                    <label for="vm_ip_address" class="form-label fw-semibold">
                                                        <i class="fas fa-network-wired text-success me-1"></i>IP Address VM
                                                    </label>
                                                    <input type="text" name="vm_ip_address" id="vm_ip_address"
                                                        class="form-control form-control-lg" value="{{ old('vm_ip_address') }}"
                                                        placeholder="Contoh: 192.168.1.100 atau 10.0.0.5">
                                                    @error('vm_ip_address')
                                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted">IP Address yang akan digunakan user untuk mengakses VM</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info border-0 bg-opacity-25">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Info:</strong> Pastikan semua data sudah benar sebelum menyimpan. Tanggal selesai
                                harus lebih besar atau sama dengan tanggal mulai.
                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <a href="{{ route('rentals.index') }}" class="btn btn-lg btn-secondary px-4">
                                    <i class="fas fa-times me-2"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-lg btn-success px-4">
                                    <i class="fas fa-save me-2"></i>Simpan Rental
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <!-- Flatpickr CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        
        <style>
            .form-select-lg,
            .form-control-lg {
                border-radius: 8px;
                border: 2px solid #e0e0e0;
                transition: all 0.3s ease;
            }

            .form-select-lg:focus,
            .form-control-lg:focus {
                border-color: #667eea;
                box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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

            /* Flatpickr custom styling */
            .flatpickr-calendar {
                border-radius: 8px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            }

            .flatpickr-day.selected {
                background: #667eea;
                border-color: #667eea;
            }

            .flatpickr-day.selected:hover {
                background: #764ba2;
                border-color: #764ba2;
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Flatpickr JS -->
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Password toggle functionality
                const togglePassword = document.getElementById('togglePassword');
                const passwordInput = document.getElementById('vm_password');
                const toggleIcon = document.getElementById('togglePasswordIcon');

                if (togglePassword) {
                    togglePassword.addEventListener('click', function() {
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

                // Initialize Flatpickr for date inputs with DD/MM/YYYY format
                const dateConfig = {
                    dateFormat: "d/m/Y",
                    locale: "id",
                    allowInput: true,
                    disableMobile: true,
                    onReady: function(selectedDates, dateStr, instance) {
                        instance.calendarContainer.classList.add('flatpickr-custom');
                    }
                };

                // Start date picker
                const startDatePicker = flatpickr("#start_date", {
                    ...dateConfig,
                    onChange: function(selectedDates, dateStr, instance) {
                        // Update end date minimum to start date
                        if (endDatePicker && selectedDates.length > 0) {
                            endDatePicker.set('minDate', selectedDates[0]);
                        }
                    }
                });

                // End date picker
                const endDatePicker = flatpickr("#end_date", {
                    ...dateConfig,
                    onChange: function(selectedDates, dateStr, instance) {
                        // Validate that end date is after start date
                        const startDate = startDatePicker.selectedDates[0];
                        if (startDate && selectedDates.length > 0 && selectedDates[0] < startDate) {
                            alert('Tanggal selesai harus lebih besar dari tanggal mulai!');
                            instance.clear();
                        }
                    }
                });

                // Form validation before submit
                const form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const startDate = document.getElementById('start_date').value;
                        const endDate = document.getElementById('end_date').value;

                        if (!startDate || !endDate) {
                            e.preventDefault();
                            alert('Harap isi tanggal mulai dan tanggal selesai!');
                            return false;
                        }

                        // Parse dates in DD/MM/YYYY format
                        const parseDate = (dateStr) => {
                            const parts = dateStr.split('/');
                            return new Date(parts[2], parts[1] - 1, parts[0]);
                        };

                        const start = parseDate(startDate);
                        const end = parseDate(endDate);

                        if (end < start) {
                            e.preventDefault();
                            alert('Tanggal selesai harus lebih besar atau sama dengan tanggal mulai!');
                            return false;
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection