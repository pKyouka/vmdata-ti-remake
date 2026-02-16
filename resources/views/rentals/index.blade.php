@extends('layouts.app')

@section('title', 'VMDATA TI')
@section('page-title', 'Rentals')
@section('content')
    <div class="container-fluid px-4 py-4">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1 fw-bold text-psti-navy">Rental Management</h2>
                <p class="text-muted mb-0">Kelola semua rental VM dengan mudah dan efisien</p>
            </div>
            <a href="{{ route('rentals.create') }}" class="btn btn-psti-navy shadow-sm">
                <i class="fas fa-plus-circle me-2"></i>Tambah Rental
            </a>
        </div>

        {{-- Stats Cards --}}
        @php
            $totalPending = (!empty($pendingVmrentals) ? $pendingVmrentals->where('status', 'pending')->count() : 0);
            $totalActive = (!empty($vmrentals) ? $vmrentals->where('status', 'active')->count() : 0) + (!empty($rentals) ? $rentals->where('status', 'active')->count() : 0);
            $totalAll = (!empty($pendingVmrentals) ? $pendingVmrentals->count() : 0) + (!empty($vmrentals) ? $vmrentals->count() : 0) + (!empty($rentals) ? $rentals->count() : 0);
        @endphp

        <div class="row g-4 mb-4">
            <!-- Total Rentals -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm overflow-hidden h-100">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center relative z-10">
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-2 small tracking-wide">Total Rentals</h6>
                                <h2 class="mb-0 fw-bold text-psti-navy">{{ $totalAll }}</h2>
                                <p class="mb-0 small text-success mt-1"><i class="fas fa-arrow-up me-1"></i> Data diperbarui
                                </p>
                            </div>
                            <div class="icon-shape bg-psti-navy text-white rounded-3 p-3 shadow-sm">
                                <i class="fas fa-layer-group fa-2x"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 h-1 bg-psti-navy"></div>
                    </div>
                </div>
            </div>

            <!-- Active Rentals -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm overflow-hidden h-100">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center relative z-10">
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-2 small tracking-wide">Active Rentals</h6>
                                <h2 class="mb-0 fw-bold text-success">{{ $totalActive }}</h2>
                                <p class="mb-0 small text-muted mt-1">Sedang berjalan</p>
                            </div>
                            <div class="icon-shape bg-success bg-gradient text-white rounded-3 p-3 shadow-sm">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 h-1 bg-success"></div>
                    </div>
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm overflow-hidden h-100">
                    <div class="card-body p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center relative z-10">
                            <div>
                                <h6 class="text-uppercase text-muted fw-bold mb-2 small tracking-wide">Pending Requests</h6>
                                <h2 class="mb-0 fw-bold text-psti-gold">{{ $totalPending }}</h2>
                                <p class="mb-0 small text-muted mt-1">Menunggu persetujuan</p>
                            </div>
                            <div class="icon-shape bg-warning bg-gradient text-white rounded-3 p-3 shadow-sm">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100 h-1 bg-warning"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search and Filter --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <form method="GET" action="{{ route('rentals.index') }}">
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label small text-muted fw-bold">Pencarian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0 text-muted ps-3">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" name="search"
                                    placeholder="Cari berdasarkan User, VM, atau IP..." value="{{ request('search') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted fw-bold">Filter Status</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired
                                </option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-psti-navy w-100">
                                <i class="fas fa-filter me-1"></i> Filter Data
                            </button>
                            <a href="{{ route('rentals.index') }}" class="btn btn-light w-100 border text-muted">
                                <i class="fas fa-sync-alt me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Pending Requests Section --}}
        @if(!empty($pendingVmrentals) && $pendingVmrentals->where('status', 'pending')->count() > 0)
            <div class="card shadow-sm mb-4 border-warning border-start border-4">
                <div class="card-header bg-warning bg-opacity-10 border-0 py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-bell me-2 text-warning"></i>
                        Permintaan Pending <span
                            class="badge bg-warning text-dark ms-2">{{ $pendingVmrentals->where('status', 'pending')->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-4">ID</th>
                                    <th>User</th>
                                    <th>VM</th>
                                    <th>Tanggal</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingVmrentals->where('status', 'pending') as $pv)
                                    <tr>
                                        <td class="px-4"><span class="badge bg-light text-dark">#{{ $pv->id }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php $theUser = $pv->user ?? null; @endphp
                                                <div class="avatar-circle me-2">
                                                    @if($theUser && $theUser->avatar)
                                                        <img src="{{ asset('storage/' . $theUser->avatar) }}" alt="avatar"
                                                            class="rounded-circle" width="40" height="40">
                                                    @else
                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                            style="width: 40px; height: 40px; font-weight: 600;">
                                                            {{ substr($theUser->name ?? 'U', 0, 1) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $theUser->name ?? '-' }}</div>
                                                    <small class="text-muted">{{ $theUser->email ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-75 px-3 py-2">
                                                <i class="fas fa-server me-1"></i>{{ $pv->vm->name ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div><small class="text-muted">Mulai:</small>
                                                {{ optional($pv->start_time)->format('d/m/Y') }}</div>
                                            <div><small class="text-muted">Selesai:</small>
                                                {{ optional($pv->end_time)->format('d/m/Y') }}</div>
                                        </td>
                                        <td>
                                            @if($pv->start_time && $pv->end_time)
                                                <span
                                                    class="badge bg-secondary">{{ \Carbon\Carbon::parse($pv->start_time)->diffInDays(\Carbon\Carbon::parse($pv->end_time)) }}
                                                    hari</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark px-3 py-2">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center">
                                                <a href="{{ route('vmrentals.show', $pv->id) }}" class="btn btn-sm btn-info"
                                                    title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <form action="{{ route('admin.vmrentals.respond', $pv->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button class="btn btn-sm btn-success" title="Setujui">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.vmrentals.respond', $pv->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button class="btn btn-sm btn-danger" title="Tolak">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- All Rentals Cards Grid --}}
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-th-large me-2 text-primary"></i>
                    Semua Rentals
                </h5>
            </div>

            {{-- Rentals Grid --}}
            <div class="row g-4">
                {{-- Processed VM Rentals --}}
                @if(!empty($pendingVmrentals))
                    @foreach($pendingVmrentals->where('status', '!=', 'pending') as $pv)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 rental-card">
                                <div class="card-body">
                                    {{-- Header with ID and Status --}}
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-light text-dark fw-semibold">#{{ $pv->id }}</span>
                                        @php
                                            $st = strtolower($pv->status);
                                            $badgeClass = match ($st) {
                                                'active' => 'bg-success',
                                                'cancelled' => 'bg-secondary',
                                                'expired' => 'bg-danger',
                                                default => 'bg-warning'
                                            };
                                            $icon = match ($st) {
                                                'active' => 'fa-check-circle',
                                                'cancelled' => 'fa-ban',
                                                'expired' => 'fa-exclamation-triangle',
                                                default => 'fa-clock'
                                            };
                                        @endphp
                                        @if(auth()->user() && auth()->user()->isAdmin())
                                            <button type="button" class="btn p-0 border-0 status-badge-btn-rental" data-model="vmrental"
                                                data-rental-id="{{ $pv->id }}" data-rental-status="{{ $pv->status }}">
                                                <span class="badge {{ $badgeClass }} px-3 py-2">
                                                    <i class="fas {{ $icon }} me-1"></i>{{ ucfirst($pv->status) }}
                                                </span>
                                            </button>
                                        @else
                                            <span class="badge {{ $badgeClass }} px-3 py-2">
                                                <i class="fas {{ $icon }} me-1"></i>{{ ucfirst($pv->status) }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- User Info --}}
                                    <div class="d-flex align-items-center mb-3">
                                        @php $theUser = $pv->user ?? null; @endphp
                                        <div class="avatar-circle me-3">
                                            @if($theUser && $theUser->avatar)
                                                <img src="{{ asset('storage/' . $theUser->avatar) }}" alt="avatar"
                                                    class="rounded-circle" width="48" height="48">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px; font-weight: 600; font-size: 1.2rem;">
                                                    {{ substr($theUser->name ?? 'U', 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-bold text-truncate">{{ $theUser->name ?? '-' }}</div>
                                            <small class="text-muted text-truncate d-block">{{ $theUser->email ?? '' }}</small>
                                        </div>
                                    </div>

                                    {{-- VM Info --}}
                                    <div class="mb-3">
                                        <span class="badge bg-info bg-opacity-75 px-3 py-2 w-100 text-start">
                                            <i class="fas fa-server me-2"></i>{{ $pv->vm->name ?? '-' }}
                                        </span>
                                    </div>

                                    {{-- Date Range --}}
                                    <div class="mb-3 small">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted"><i class="fas fa-calendar-plus me-1"></i> Mulai:</span>
                                            <span class="fw-semibold">{{ optional($pv->start_time)->format('d/m/Y') ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted"><i class="fas fa-calendar-check me-1"></i> Selesai:</span>
                                            <span class="fw-semibold">{{ optional($pv->end_time)->format('d/m/Y') ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted"><i class="fas fa-clock me-1"></i> Durasi:</span>
                                            @if($pv->start_time && $pv->end_time)
                                                <span
                                                    class="badge bg-secondary">{{ \Carbon\Carbon::parse($pv->start_time)->diffInDays(\Carbon\Carbon::parse($pv->end_time)) }}
                                                    hari</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- PJ Admin --}}
                                    <div class="mb-3 pb-3 border-bottom small">
                                        <span class="text-muted"><i class="fas fa-user-shield me-1"></i> PJ:</span>
                                        <span class="fw-semibold">{{ $pv->admin->name ?? 'Admin' }}</span>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('vmrentals.show', $pv->id) }}" class="btn btn-sm btn-info flex-grow-1"
                                            title="Detail">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('vmrentals.edit', $pv->id) }}" class="btn btn-sm btn-warning"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="confirmDeleteVM({{ $pv->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-vm-form-{{ $pv->id }}" action="{{ route('vmrentals.destroy', $pv->id) }}"
                                                method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- VM Rentals --}}
                @if(!empty($vmrentals))
                    @foreach($vmrentals as $pv)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 rental-card">
                                <div class="card-body">
                                    {{-- Header with ID and Status --}}
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-light text-dark fw-semibold">#{{ $pv->id }}</span>
                                        @php
                                            $st = strtolower($pv->status);
                                            $badgeClass = match ($st) {
                                                'active' => 'bg-success',
                                                'cancelled' => 'bg-secondary',
                                                'expired' => 'bg-danger',
                                                default => 'bg-warning'
                                            };
                                            $icon = match ($st) {
                                                'active' => 'fa-check-circle',
                                                'cancelled' => 'fa-ban',
                                                'expired' => 'fa-exclamation-triangle',
                                                default => 'fa-clock'
                                            };
                                        @endphp
                                        @if(auth()->user() && auth()->user()->isAdmin())
                                            <button type="button" class="btn p-0 border-0 status-badge-btn-rental" data-model="vmrental"
                                                data-rental-id="{{ $pv->id }}" data-rental-status="{{ $pv->status }}">
                                                <span class="badge {{ $badgeClass }} px-3 py-2">
                                                    <i class="fas {{ $icon }} me-1"></i>{{ ucfirst($pv->status) }}
                                                </span>
                                            </button>
                                        @else
                                            <span class="badge {{ $badgeClass }} px-3 py-2">
                                                <i class="fas {{ $icon }} me-1"></i>{{ ucfirst($pv->status) }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- User Info --}}
                                    <div class="d-flex align-items-center mb-3">
                                        @php $theUser = $pv->user ?? null; @endphp
                                        <div class="avatar-circle me-3">
                                            @if($theUser && $theUser->avatar)
                                                <img src="{{ asset('storage/' . $theUser->avatar) }}" alt="avatar"
                                                    class="rounded-circle" width="48" height="48">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px; font-weight: 600; font-size: 1.2rem;">
                                                    {{ substr($theUser->name ?? 'U', 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-bold text-truncate">{{ $theUser->name ?? '-' }}</div>
                                            <small class="text-muted text-truncate d-block">{{ $theUser->email ?? '' }}</small>
                                        </div>
                                    </div>

                                    {{-- VM Info --}}
                                    <div class="mb-3">
                                        <span class="badge bg-info bg-opacity-75 px-3 py-2 w-100 text-start">
                                            <i class="fas fa-server me-2"></i>{{ $pv->vm->name ?? '-' }}
                                        </span>
                                    </div>

                                    {{-- Date Range --}}
                                    <div class="mb-3 small">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted"><i class="fas fa-calendar-plus me-1"></i> Mulai:</span>
                                            <span class="fw-semibold">{{ optional($pv->start_time)->format('d/m/Y') ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted"><i class="fas fa-calendar-check me-1"></i> Selesai:</span>
                                            <span class="fw-semibold">{{ optional($pv->end_time)->format('d/m/Y') ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted"><i class="fas fa-clock me-1"></i> Durasi:</span>
                                            @if($pv->start_time && $pv->end_time)
                                                <span
                                                    class="badge bg-secondary">{{ \Carbon\Carbon::parse($pv->start_time)->diffInDays(\Carbon\Carbon::parse($pv->end_time)) }}
                                                    hari</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- PJ Admin --}}
                                    <div class="mb-3 pb-3 border-bottom small">
                                        <span class="text-muted"><i class="fas fa-user-shield me-1"></i> PJ:</span>
                                        <span class="fw-semibold">{{ $pv->admin->name ?? 'Admin' }}</span>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('vmrentals.show', $pv->id) }}" class="btn btn-sm btn-info flex-grow-1"
                                            title="Detail">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        @if(auth()->user()->isAdmin())
                                            <a href="{{ route('vmrentals.edit', $pv->id) }}" class="btn btn-sm btn-warning"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                                onclick="confirmDeleteVM({{ $pv->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-vm-form-{{ $pv->id }}" action="{{ route('vmrentals.destroy', $pv->id) }}"
                                                method="POST" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Regular Rentals --}}
                @if(!empty($rentals) && $rentals->count())
                    @foreach($rentals as $rental)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 shadow-sm border-0 rental-card">
                                <div class="card-body">
                                    {{-- Header with ID and Status --}}
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <span class="badge bg-light text-dark fw-semibold">#{{ $rental->id }}</span>
                                        @php
                                            $st = strtolower($rental->status ?? 'active');
                                            $badgeClass = match ($st) {
                                                'active' => 'bg-success',
                                                'cancelled' => 'bg-secondary',
                                                'expired' => 'bg-danger',
                                                'inactive' => 'bg-secondary',
                                                default => 'bg-warning'
                                            };
                                            $icon = match ($st) {
                                                'active' => 'fa-check-circle',
                                                'cancelled' => 'fa-ban',
                                                'expired' => 'fa-exclamation-triangle',
                                                'inactive' => 'fa-pause-circle',
                                                default => 'fa-clock'
                                            };
                                        @endphp
                                        @if(auth()->user() && auth()->user()->isAdmin())
                                            <button type="button" class="btn p-0 border-0 status-badge-btn-rental" data-model="rental"
                                                data-rental-id="{{ $rental->id }}"
                                                data-rental-status="{{ $rental->status ?? 'active' }}">
                                                <span class="badge {{ $badgeClass }} px-3 py-2">
                                                    <i class="fas {{ $icon }} me-1"></i>{{ ucfirst($rental->status ?? 'Active') }}
                                                </span>
                                            </button>
                                        @else
                                            <span class="badge {{ $badgeClass }} px-3 py-2">
                                                <i class="fas {{ $icon }} me-1"></i>{{ ucfirst($rental->status ?? 'Active') }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- User Info --}}
                                    <div class="d-flex align-items-center mb-3">
                                        @php $theUser = $rental->user ?? null; @endphp
                                        <div class="avatar-circle me-3">
                                            @if($theUser && $theUser->avatar)
                                                <img src="{{ asset('storage/' . $theUser->avatar) }}" alt="avatar"
                                                    class="rounded-circle" width="48" height="48">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                    style="width: 48px; height: 48px; font-weight: 600; font-size: 1.2rem;">
                                                    {{ substr($theUser->name ?? 'U', 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <div class="fw-bold text-truncate">{{ $theUser->name ?? '-' }}</div>
                                            <small class="text-muted text-truncate d-block">{{ $theUser->email ?? '' }}</small>
                                        </div>
                                    </div>

                                    {{-- VM Info with Specs --}}
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <span class="badge bg-info bg-opacity-75 px-3 py-2 flex-grow-1 text-start">
                                                <i class="fas fa-server me-2"></i>{{ $rental->vm->name ?? '-' }}
                                            </span>
                                        </div>
                                        <div class="row g-2 small">
                                            <div class="col-4">
                                                <div class="bg-light rounded p-2 text-center">
                                                    <div class="text-muted" style="font-size: 0.7rem;">RAM</div>
                                                    <div class="fw-bold text-primary">{{ $rental->vm->ram ?? 0 }}GB</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="bg-light rounded p-2 text-center">
                                                    <div class="text-muted" style="font-size: 0.7rem;">CPU</div>
                                                    <div class="fw-bold text-success">{{ $rental->vm->cpu ?? 0 }}</div>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="bg-light rounded p-2 text-center">
                                                    <div class="text-muted" style="font-size: 0.7rem;">Storage</div>
                                                    <div class="fw-bold text-warning">{{ $rental->vm->storage ?? 0 }}GB</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- IP Address --}}
                                    @if($rental->vm_ip_address)
                                        <div class="mb-3">
                                            <div class="bg-light rounded p-2">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <small class="text-muted d-block" style="font-size: 0.7rem;">
                                                            <i class="fas fa-network-wired me-1"></i>IP Address
                                                        </small>
                                                        <span
                                                            class="fw-bold font-monospace text-success">{{ $rental->vm_ip_address }}</span>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-success copy-btn-ip"
                                                        data-clipboard-text="{{ $rental->vm_ip_address }}" title="Copy IP">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Date Range --}}
                                    <div class="mb-3 small">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted"><i class="fas fa-calendar-plus me-1"></i> Mulai:</span>
                                            <span
                                                class="fw-semibold">{{ optional($rental->start_date)->format('d/m/Y') ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted"><i class="fas fa-calendar-check me-1"></i> Selesai:</span>
                                            <span
                                                class="fw-semibold">{{ optional($rental->end_date)->format('d/m/Y') ?? '-' }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted"><i class="fas fa-clock me-1"></i> Durasi:</span>
                                            @if($rental->start_date && $rental->end_date)
                                                @php
                                                    $startDate = \Carbon\Carbon::parse($rental->start_date);
                                                    $endDate = \Carbon\Carbon::parse($rental->end_date);
                                                    $diffInDays = $startDate->diffInDays($endDate);
                                                @endphp
                                                <span class="badge bg-secondary">{{ $diffInDays }} hari</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- PJ Admin --}}
                                    <div class="mb-3 pb-3 border-bottom small">
                                        <span class="text-muted"><i class="fas fa-user-shield me-1"></i> PJ:</span>
                                        <span class="fw-semibold">{{ $rental->admin->name ?? 'Admin' }}</span>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('rentals.show', $rental->id) }}" class="btn btn-sm btn-info flex-grow-1"
                                            title="Detail">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </a>
                                        <a href="{{ route('rentals.edit', $rental->id) }}" class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" title="Hapus"
                                            onclick="confirmDelete({{ $rental->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-form-{{ $rental->id }}"
                                            action="{{ route('rentals.destroy', $rental->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Empty State --}}
                @php
                    $totalRows = 0;
                    $totalRows += (!empty($pendingVmrentals) ? $pendingVmrentals->count() : 0);
                    $totalRows += (!empty($vmrentals) ? $vmrentals->count() : 0);
                    $totalRows += (!empty($rentals) ? $rentals->count() : 0);
                @endphp

                @if($totalRows === 0)
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-4x mb-3 opacity-25"></i>
                                    <h5>Belum ada data rental</h5>
                                    <p class="mb-3">Klik tombol "Tambah Rental" untuk menambah data baru.</p>
                                    <a href="{{ route('rentals.create') }}" class="btn btn-psti-navy mt-2">
                                        <i class="fas fa-plus-circle me-2"></i>Tambah Rental
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if($rentals->hasPages())
                <div class="mt-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Menampilkan <span class="fw-bold">{{ $rentals->firstItem() }} -
                                        {{ $rentals->lastItem() }}</span>
                                    dari <span class="fw-bold">{{ $rentals->total() }}</span> data
                                </div>
                                <div>
                                    {{ $rentals->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Status Change Modal --}}
    <div class="modal fade" id="rentalStatusModal" tabindex="-1" aria-labelledby="rentalStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title" id="rentalStatusModalLabel">
                        <i class="fas fa-exchange-alt me-2"></i>Ubah Status Rental
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-3">Rental: <strong id="rentalStatusModalName" class="text-primary"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Pilih status baru:</label>
                        <div class="d-grid gap-2">
                            <label class="btn btn-outline-success text-start d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="rental_status" value="active">
                                <div>
                                    <div class="fw-semibold">Active</div>
                                    <small class="text-muted">Rental sedang aktif</small>
                                </div>
                            </label>
                            <label class="btn btn-outline-warning text-start d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="rental_status" value="pending">
                                <div>
                                    <div class="fw-semibold">Pending</div>
                                    <small class="text-muted">Menunggu persetujuan</small>
                                </div>
                            </label>
                            <label class="btn btn-outline-danger text-start d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="rental_status" value="expired">
                                <div>
                                    <div class="fw-semibold">Expired</div>
                                    <small class="text-muted">Rental sudah berakhir</small>
                                </div>
                            </label>
                            <label class="btn btn-outline-secondary text-start d-flex align-items-center">
                                <input class="form-check-input me-3" type="radio" name="rental_status" value="cancelled">
                                <div>
                                    <div class="fw-semibold">Cancelled</div>
                                    <small class="text-muted">Rental dibatalkan</small>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="button" id="confirmRentalStatusChangeBtn" class="btn btn-primary">
                        <i class="fas fa-check me-1"></i>Update Status
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDelete(rentalId) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + rentalId).submit();
                    }
                });
            }

            function confirmDeleteVM(vmrentalId) {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Permintaan sewa VM ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-vm-form-' + vmrentalId).submit();
                    }
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('rentalStatusModal');
                if (!modalEl) return;
                const modal = new bootstrap.Modal(modalEl);
                let currentRentalId = null;

                document.querySelectorAll('.status-badge-btn-rental').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        currentRentalId = this.dataset.rentalId;
                        const current = this.dataset.rentalStatus;
                        const model = this.dataset.model || 'rental';
                        modalEl.dataset.model = model;
                        const tr = this.closest('tr');
                        const nameEl = document.getElementById('rentalStatusModalName');
                        if (tr && nameEl) {
                            const vmCell = tr.querySelector('td:nth-child(3) .badge, td:nth-child(3)');
                            nameEl.textContent = vmCell ? vmCell.textContent.trim() : ('Rental ' + currentRentalId);
                        }

                        modalEl.querySelectorAll('input[name="rental_status"]').forEach(function (r) {
                            r.checked = (r.value === current);
                        });
                        modal.show();
                    });
                });

                const confirmBtn = document.getElementById('confirmRentalStatusChangeBtn');
                confirmBtn && confirmBtn.addEventListener('click', function () {
                    if (!currentRentalId) return;
                    const selected = modalEl.querySelector('input[name="rental_status"]:checked');
                    if (!selected) {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Pilih status terlebih dahulu' });
                        return;
                    }
                    const chosen = selected.value;
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const model = modalEl.dataset.model || 'rental';
                    const url = (model === 'vmrental' ? '/vmrentals/' + currentRentalId + '/status' : '/rentals/' + currentRentalId + '/status');

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: chosen })
                    }).then(res => res.json()).then(data => {
                        if (data && data.success) {
                            const btn = document.querySelector('.status-badge-btn-rental[data-rental-id="' + currentRentalId + '"]');
                            if (btn) {
                                let cls = 'bg-warning';
                                let icon = 'fa-clock';
                                if (chosen === 'active') { cls = 'bg-success'; icon = 'fa-check-circle'; }
                                else if (chosen === 'cancelled' || chosen === 'inactive') { cls = 'bg-secondary'; icon = 'fa-ban'; }
                                else if (chosen === 'expired') { cls = 'bg-danger'; icon = 'fa-exclamation-triangle'; }
                                else if (chosen === 'pending') { cls = 'bg-warning'; icon = 'fa-clock'; }

                                btn.innerHTML = '<span class="badge ' + cls + ' px-3 py-2"><i class="fas ' + icon + ' me-1"></i>' + (chosen.charAt(0).toUpperCase() + chosen.slice(1)) + '</span>';
                                btn.dataset.rentalStatus = chosen;
                            }
                            modal.hide();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message || 'Status berhasil diperbarui',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: (data && data.message) ? data.message : 'Gagal memperbarui status' });
                        }
                    }).catch(err => {
                        console.error(err);
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan saat menghubungi server.' });
                    });
                });
            });
            // Copy IP Address to clipboard
            document.querySelectorAll('.copy-btn-ip').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const text = this.getAttribute('data-clipboard-text');
                    navigator.clipboard.writeText(text).then(() => {
                        const originalIcon = this.querySelector('i');
                        const originalClass = originalIcon.className;

                        // Change icon to check
                        originalIcon.className = 'fas fa-check';
                        this.classList.remove('btn-outline-success');
                        this.classList.add('btn-success');

                        // Reset after 2 seconds
                        setTimeout(() => {
                            originalIcon.className = originalClass;
                            this.classList.remove('btn-success');
                            this.classList.add('btn-outline-success');
                        }, 2000);
                    }).catch(err => {
                        console.error('Failed to copy:', err);
                    });
                });
            });
        </script>
    @endpush

    @push('styles')
        <style>
            .rental-card {
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
            }

            .rental-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12) !important;
            }

            .table-hover tbody tr {
                transition: all 0.2s ease;
            }

            .table-hover tbody tr:hover {
                background-color: rgba(0, 123, 255, .05);
                transform: scale(1.001);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            .btn-sm {
                padding: 0.375rem 0.75rem;
                transition: all 0.2s ease;
            }

            .btn-sm:hover {
                transform: translateY(-2px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }

            .card {
                transition: all 0.3s ease;
                border-radius: 12px;
                overflow: hidden;
            }

            .badge {
                font-weight: 500;
                letter-spacing: 0.3px;
            }

            .avatar-circle img {
                object-fit: cover;
                border: 2px solid #fff;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            thead th {
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.5px;
                color: #6c757d;
            }

            .modal-content {
                border-radius: 12px;
            }

            .form-check-input:checked {
                background-color: #0d6efd;
                border-color: #0d6efd;
            }

            .btn-outline-success:has(.form-check-input:checked),
            .btn-outline-warning:has(.form-check-input:checked),
            .btn-outline-danger:has(.form-check-input:checked),
            .btn-outline-secondary:has(.form-check-input:checked) {
                background-color: rgba(13, 110, 253, 0.1);
                border-color: #0d6efd;
            }
        </style>
    @endpush
@endsection