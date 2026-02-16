@extends('layouts.app')

@section('title', 'Tambah VM - PSTI VM Rentals')
@section('page-title', 'Tambah Virtual Machine')

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
                        <h2 class="mb-1 fw-bold text-psti-navy">Buat Virtual Machine Baru</h2>
                        <p class="text-muted mb-0">Konfigurasikan spesifikasi dan pengaturan untuk VM baru.</p>
                    </div>
                </div>

                <form action="{{ route('vms.store') }}" method="POST" id="vmForm">
                    @csrf
                    @if(isset($rental))
                        <input type="hidden" name="rental_id" value="{{ $rental->id }}">
                    @endif

                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 fw-bold text-psti-navy">
                                <i class="fas fa-desktop me-2"></i>Informasi VM
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Nama VM -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold text-muted small text-uppercase">Nama VM
                                        <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-lg bg-light" id="name" name="name"
                                        placeholder="Contoh: Web-Server-01" required>
                                    <div class="form-text">Gunakan nama yang unik dan deskriptif.</div>
                                </div>

                                <!-- Server Host -->
                                <div class="col-md-6">
                                    <label for="server_id" class="form-label fw-bold text-muted small text-uppercase">Server
                                        Host <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-lg bg-light" id="server_id" name="server_id"
                                        required>
                                        <option value="" disabled selected>Pilih Server Fisik...</option>
                                        @foreach($servers as $server)
                                            <option value="{{ $server->id }}" {{ (old('server_id', isset($selectedServerId) ? $selectedServerId : null) == $server->id) ? 'selected' : '' }}>
                                                {{ $server->name }} ({{ $server->ip_address ?? 'No IP' }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text">Pilih server fisik tempat VM akan dibuat.</div>
                                </div>

                                <!-- Kategori -->
                                <div class="col-12">
                                    <label for="category_id"
                                        class="form-label fw-bold text-muted small text-uppercase">Kategori <span
                                            class="text-danger">*</span></label>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="card h-100 border-0 shadow-sm cursor-pointer hover-card">
                                                <div class="card-body text-center p-3">
                                                    <input type="radio" name="category_id" value="basic" class="d-none peer"
                                                        required {{ old('category_id') == 'basic' ? 'checked' : '' }}>
                                                    <div
                                                        class="icon-shape bg-light text-muted rounded-circle mb-3 mx-auto peer-checked-bg-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="fas fa-feather-alt fa-lg"></i>
                                                    </div>
                                                    <h6 class="fw-bold mb-1">Basic</h6>
                                                    <small class="text-muted d-block">Ringan & Hemat</small>
                                                </div>
                                                <!-- Ring Indicator -->
                                                <div class="position-absolute top-0 start-0 w-100 h-100 border border-2 border-transparent rounded-3 peer-checked-border-primary"
                                                    style="pointer-events: none;"></div>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="card h-100 border-0 shadow-sm cursor-pointer hover-card">
                                                <div class="card-body text-center p-3">
                                                    <input type="radio" name="category_id" value="standard"
                                                        class="d-none peer" {{ old('category_id') == 'standard' ? 'checked' : '' }}>
                                                    <div
                                                        class="icon-shape bg-light text-muted rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="fas fa-layer-group fa-lg"></i>
                                                    </div>
                                                    <h6 class="fw-bold mb-1">Standard</h6>
                                                    <small class="text-muted d-block">Aplikasi Umum</small>
                                                </div>
                                                <div class="position-absolute top-0 start-0 w-100 h-100 border border-2 border-transparent rounded-3 peer-checked-border-primary"
                                                    style="pointer-events: none;"></div>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="card h-100 border-0 shadow-sm cursor-pointer hover-card">
                                                <div class="card-body text-center p-3">
                                                    <input type="radio" name="category_id" value="premium"
                                                        class="d-none peer" {{ old('category_id') == 'premium' ? 'checked' : '' }}>
                                                    <div
                                                        class="icon-shape bg-light text-muted rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="fas fa-rocket fa-lg"></i>
                                                    </div>
                                                    <h6 class="fw-bold mb-1">Premium</h6>
                                                    <small class="text-muted d-block">Performa Tinggi</small>
                                                </div>
                                                <div class="position-absolute top-0 start-0 w-100 h-100 border border-2 border-transparent rounded-3 peer-checked-border-primary"
                                                    style="pointer-events: none;"></div>
                                            </label>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="card h-100 border-0 shadow-sm cursor-pointer hover-card">
                                                <div class="card-body text-center p-3">
                                                    <input type="radio" name="category_id" value="elite" class="d-none peer"
                                                        {{ old('category_id') == 'elite' ? 'checked' : '' }}>
                                                    <div
                                                        class="icon-shape bg-light text-muted rounded-circle mb-3 mx-auto d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="fas fa-crown fa-lg"></i>
                                                    </div>
                                                    <h6 class="fw-bold mb-1">Elite</h6>
                                                    <small class="text-muted d-block">Enterprise Grade</small>
                                                </div>
                                                <div class="position-absolute top-0 start-0 w-100 h-100 border border-2 border-transparent rounded-3 peer-checked-border-primary"
                                                    style="pointer-events: none;"></div>
                                            </label>
                                        </div>
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
                                        <input type="number" class="form-control" name="cpu" min="1" max="64"
                                            value="{{ old('cpu', isset($rental) ? $rental->cpu : 1) }}" required>
                                        <span class="input-group-text bg-light">Core</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">RAM Memory</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-memory"></i></span>
                                        <input type="number" class="form-control" name="ram" min="1" max="128"
                                            value="{{ old('ram', isset($rental) ? $rental->ram : 2) }}" required>
                                        <span class="input-group-text bg-light">GB</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Storage</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-hdd"></i></span>
                                        <input type="number" class="form-control" name="storage" min="10" max="2000"
                                            value="{{ old('storage', isset($rental) ? $rental->storage : 20) }}" required>
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
                                    <label class="form-label fw-bold text-muted small text-uppercase">IP Address
                                        (Opsional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-globe"></i></span>
                                        <input type="text" class="form-control" name="ip_address"
                                            placeholder="Contoh: 192.168.1.10" pattern="^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$">
                                    </div>
                                    <div class="form-text">Biarkan kosong jika menggunakan DHCP.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Status Awal</label>
                                    <select class="form-select bg-light" name="status">
                                        <option value="available" selected>Available (Siap Pakai)</option>
                                        <option value="maintenance">Maintenance</option>
                                        <option value="offline">Offline</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Username Akses</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-user"></i></span>
                                        <input type="text" class="form-control" name="access_username"
                                            placeholder="Username login VM">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">Password Akses</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="fas fa-key"></i></span>
                                        <input type="password" class="form-control" name="access_password"
                                            placeholder="Password login VM">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Deskripsi /
                                    Catatan</label>
                                <textarea class="form-control" name="description" rows="3"
                                    placeholder="Informasi tambahan mengenai VM ini...">{{ old('description', isset($rental) ? $rental->purpose : '') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-3 mb-5">
                        <a href="{{ route('vms.index') }}" class="btn btn-light border px-4">Batal</a>
                        <button type="submit" class="btn btn-psti-navy px-5 shadow-sm">
                            <i class="fas fa-save me-2"></i>Simpan VM Baru
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection