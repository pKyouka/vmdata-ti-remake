@extends('layouts.app')

@section('title', 'Detail Virtual Machine - PSTI VM Rentals')
@section('page-title', 'Detail Virtual Machine')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 text-psti-navy fw-bold mb-1">{{ $vm->name }}</h1>
                <p class="text-muted mb-0">Detail informasi dan konfigurasi Virtual Machine</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('vms.index') }}" class="btn btn-light border shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <a href="{{ route('vms.edit', $vm->id) }}" class="btn btn-warning text-white shadow-sm">
                    <i class="fas fa-edit me-2"></i>Edit VM
                </a>
                @if(auth()->user()->isAdmin())
                    <form action="{{ route('vms.destroy', $vm->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger shadow-sm"
                            onclick="return confirm('Yakin ingin menghapus VM ini secara permanen?')">
                            <i class="fas fa-trash-alt me-2"></i>Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Alert / Notifications -->
        @if(session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <div class="fs-4 me-3"><i class="fas fa-exclamation-circle"></i></div>
                    <div>
                        <div>{{ session('error') }}</div>
                        @if(session('blocking_rental_ids'))
                            <div class="mt-1 small">
                                <strong>Blocking rentals:</strong>
                                @foreach(session('blocking_rental_ids') as $rid)
                                    <a href="{{ route('rentals.show', $rid) }}"
                                        class="badge bg-white text-danger border border-danger ms-1">#{{ $rid }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <div class="row g-4">
            <!-- Main Info -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-psti-navy">
                            <i class="fas fa-info-circle me-2"></i>Informasi Umum
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Status Operasional</label>
                                <div>
                                    @php $s = $vm->status ?? 'unknown'; @endphp
                                    @if($s === 'available')
                                        <span class="badge bg-success rounded-pill px-3 py-2"><i
                                                class="fas fa-check-circle me-1"></i> Available</span>
                                    @elseif($s === 'rented')
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2"><i
                                                class="fas fa-user-clock me-1"></i> Rented</span>
                                    @elseif($s === 'maintenance')
                                        <span class="badge bg-danger rounded-pill px-3 py-2"><i class="fas fa-tools me-1"></i>
                                            Maintenance</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3 py-2">{{ ucfirst($s) }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small text-muted text-uppercase fw-bold mb-1">Server Host</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-server text-muted me-2"></i>
                                    <span class="fw-bold text-dark">{{ $vm->server->name ?? 'Unassigned' }}</span>
                                </div>
                            </div>
                        </div>

                        <hr class="bg-light">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <div class="icon-shape bg-light text-primary rounded-3 p-2">
                                            <i class="fas fa-network-wired fa-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="small text-muted text-uppercase fw-bold">IP Address</label>
                                        <div class="font-monospace fs-5 text-dark">{{ $vm->ip_address ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <div class="icon-shape bg-light text-info rounded-3 p-2">
                                            <i class="fas fa-tag fa-lg"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="small text-muted text-uppercase fw-bold">Kategori</label>
                                        <div class="fs-5 text-dark">{{ $vm->category->name ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="small text-muted text-uppercase fw-bold mb-2">Deskripsi</label>
                            <div class="p-3 bg-light rounded border text-secondary">
                                {{ $vm->description ?? 'Tidak ada deskripsi tersedia.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Specs & Access -->
            <div class="col-lg-4">
                <!-- Specs Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-gradient-psti text-white py-3"
                        style="background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%);">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-microchip me-2"></i>Spesifikasi
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span><i class="fas fa-memory text-muted me-2" style="width:20px"></i> RAM</span>
                                <span class="fw-bold">{{ $vm->ram ?? ($vm->specification->ram ?? '-') }} GB</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span><i class="fas fa-microchip text-muted me-2" style="width:20px"></i> vCPU</span>
                                <span class="fw-bold">{{ $vm->cpu ?? ($vm->specification->cpu ?? '-') }} Cores</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <span><i class="fas fa-hdd text-muted me-2" style="width:20px"></i> Storage</span>
                                <span class="fw-bold">{{ $vm->storage ?? ($vm->specification->storage ?? '-') }} GB</span>
                            </li>
                            @if($vm->os)
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <span><i class="fab fa-linux text-muted me-2" style="width:20px"></i> OS</span>
                                    <span class="fw-bold">{{ $vm->os }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <!-- Credentials Card -->
                @php
                    $canViewCreds = auth()->check() && (
                        auth()->user()->isAdmin() ||
                        optional($vm->rentals()->where('status', 'active')->latest()->first())->user_id === auth()->id()
                    );
                @endphp

                @if($canViewCreds)
                    <div class="card border-0 shadow-sm border-start border-4 border-warning">
                        <div class="card-body">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fas fa-key text-warning me-2"></i>Akses Login
                            </h6>
                            @if($vm->access_username || $vm->access_password)
                                <div class="mb-2">
                                    <label class="small text-muted">Username</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control bg-white" value="{{ $vm->access_username ?? '-' }}"
                                            readonly>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="navigator.clipboard.writeText('{{ $vm->access_username }}')"><i
                                                class="fas fa-copy"></i></button>
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <label class="small text-muted">Password</label>
                                    <div class="input-group input-group-sm">
                                        <input type="password" class="form-control bg-white"
                                            value="{{ $vm->access_password ?? '' }}" readonly id="vmPass">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass()"><i
                                                class="fas fa-eye"></i></button>
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="navigator.clipboard.writeText('{{ $vm->access_password }}')"><i
                                                class="fas fa-copy"></i></button>
                                    </div>
                                </div>
                                <div class="alert alert-warning py-2 mb-0 mt-3" style="font-size: 0.8rem;">
                                    <i class="fas fa-lock me-1"></i> Data ini rahasia. Jangan bagikan kepada pihak yang tidak
                                    berwenang.
                                </div>
                            @else
                                <p class="text-muted small fst-italic mb-0">Belum ada kredensial akses yang dikonfigurasi.</p>
                            @endif
                        </div>
                    </div>
                    <script>
                        function togglePass() {
                            var x = document.getElementById("vmPass");
                            if (x.type === "password") {
                                x.type = "text";
                            } else {
                                x.type = "password";
                            }
                        }
                    </script>
                @endif
            </div>
        </div>
    </div>
@endsection