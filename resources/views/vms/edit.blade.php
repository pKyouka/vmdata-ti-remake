@extends('layouts.app')

@section('title', 'Edit VM - PSTI VM Rentals')
@section('page-title', 'Edit Virtual Machine')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Header -->
                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('vms.index') }}" class="btn btn-light border me-3 shadow-sm rounded-circle"
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-arrow-left text-muted"></i>
                    </a>
                    <div>
                        <h2 class="mb-1 fw-bold text-psti-navy">Edit Virtual Machine</h2>
                        <p class="text-muted mb-0">Perbarui konfigurasi untuk VM: <span
                                class="fw-bold">{{ $vm->name }}</span></p>
                    </div>
                </div>

                <form action="{{ route('vms.update', $vm->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-psti-navy">
                                <i class="fas fa-desktop me-2"></i>Informasi Utama
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Nama VM -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold text-muted small text-uppercase">Nama
                                        VM</label>
                                    <input type="text" class="form-control form-control-lg bg-light" id="name" name="name"
                                        value="{{ old('name', $vm->name) }}" required>
                                </div>

                                <!-- Server Host -->
                                <div class="col-md-6">
                                    <label for="server_id" class="form-label fw-bold text-muted small text-uppercase">Server
                                        Host</label>
                                    <select class="form-select form-select-lg bg-light" id="server_id" name="server_id"
                                        required>
                                        @foreach($servers as $server)
                                            <option value="{{ $server->id }}" {{ (old('server_id', $vm->server_id) == $server->id) ? 'selected' : '' }}>
                                                {{ $server->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Kategori -->
                                @php
                                    $categories = [
                                        'basic' => ['icon' => 'fa-feather-alt', 'label' => 'Basic', 'desc' => 'Ringan & Hemat'],
                                        'standard' => ['icon' => 'fa-layer-group', 'label' => 'Standard', 'desc' => 'Aplikasi Umum'],
                                        'premium' => ['icon' => 'fa-rocket', 'label' => 'Premium', 'desc' => 'Performa Tinggi'],
                                        'elite' => ['icon' => 'fa-crown', 'label' => 'Elite', 'desc' => 'Enterprise Grade'],
                                    ];
                                    $currentSlug = strtolower(str_replace(' ', '-', optional($vm->category)->name ?? ''));
                                    // Fallback map if slug is different
                                    if (!$currentSlug && $vm->category_id) {
                                        // This logic depends on category seeding, assuming IDs match or names match keys
                                        // For simplicity, we assume one of these keys matches partially or we check names
                                    }
                                @endphp

                                <div class="col-12">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Kategori</label>
                                    <div class="row g-3">
                                        @foreach($categories as $key => $cat)
                                            <div class="col-md-3">
                                                <label class="card h-100 border-0 shadow-sm cursor-pointer hover-card">
                                                    <div class="card-body text-center p-3">
                                                        <input type="radio" name="category_id" value="{{ $key }}"
                                                            class="d-none peer" {{ (old('category_id') == $key || $currentSlug == $key || ($vm->category_id == $loop->iteration && !$currentSlug)) ? 'checked' : '' }}>
                                                        <div class="icon-shape bg-light text-muted rounded-circle mb-3 mx-auto peer-checked-bg-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                            <i class="fas {{ $cat['icon'] }} fa-lg"></i>
                                                        </div>
                                                        <h6 class="fw-bold mb-1">{{ $cat['label'] }}</h6>
                                                        <small class="text-muted d-block">{{ $cat['desc'] }}</small>
                                                    </div>
                                                    <div class="position-absolute top-0 start-0 w-100 h-100 border border-2 border-transparent rounded-3 peer-checked-border-primary"
                                                        style="pointer-events: none;"></div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <style>
                                        .peer:checked+.icon-shape {
                                            background-color: var(--psti-navy) !important;
                                            color: white !important;
                                        }

                                        .peer:checked~.fw-bold {
                                            color: var(--psti-navy) !important;
                                        }

                                        .peer-checked-border-primary {
                                            border-color: var(--psti-navy) !important;
                                        }

                                        .hover-card:hover {
                                            transform: translateY(-3px);
                                            transition: 0.3s;
                                        }
                                    </style>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-psti-navy">
                                <i class="fas fa-sliders-h me-2"></i>Spesifikasi Teknis
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">vCPU Cores</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-microchip"></i></span>
                                        <input type="number" class="form-control" name="cpu" min="1" step="1"
                                            value="{{ old('cpu', $vm->cpu ?? 1) }}" required>
                                        <span class="input-group-text bg-light">Core</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">RAM Memory</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-memory"></i></span>
                                        <input type="number" class="form-control" name="ram" min="1"
                                            value="{{ old('ram', $vm->ram) }}" required>
                                        <span class="input-group-text bg-light">GB</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Storage</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-hdd"></i></span>
                                        <input type="number" class="form-control" name="storage" min="1"
                                            value="{{ old('storage', $vm->storage) }}" required>
                                        <span class="input-group-text bg-light">GB</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-psti-navy">
                                <i class="fas fa-network-wired me-2"></i>Konfigurasi Jaringan & Akses
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">IP Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-globe"></i></span>
                                        <input type="text" class="form-control" name="ip_address"
                                            value="{{ old('ip_address', $vm->ip_address) }}"
                                            placeholder="e.g. 192.168.1.10">
                                    </div>
                                    <div class="form-text">Biarkan kosong untuk auto-assign.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Status</label>
                                    <select class="form-select bg-light" name="status">
                                        <option value="available" {{ old('status', $vm->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="rented" {{ old('status', $vm->status) == 'rented' ? 'selected' : '' }}>
                                            Rented</option>
                                        <option value="maintenance" {{ old('status', $vm->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="offline" {{ old('status', $vm->status) == 'offline' ? 'selected' : '' }}>Offline</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Username Akses</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" name="access_username"
                                            value="{{ old('access_username', $vm->access_username) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Password Akses</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" name="access_password"
                                            placeholder="Isi hanya jika ingin mengubah password">
                                    </div>
                                    <div class="form-text">Biarkan kosong jika tidak ingin mengubah password saat ini.</div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Deskripsi /
                                    Catatan</label>
                                <textarea class="form-control" name="description"
                                    rows="3">{{ old('description', $vm->description) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mb-5">
                        <a href="{{ route('vms.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-psti-navy px-5 shadow-sm">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection