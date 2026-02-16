@extends('layouts.app')

@section('title', 'Virtual Machines - PSTI VM Rentals')
@section('page-title', 'Virtual Machines')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1 fw-bold text-psti-navy">Virtual Machines</h2>
                <p class="text-muted mb-0">Kelola infrastruktur virtual machine dan server</p>
            </div>
            {{-- Button to add new VM (general) if needed, currently per server --}}
        </div>

        @php
            // Ensure we only display up to 3 servers on this page
            $displayServers = $servers ?? collect();
            if (is_array($displayServers)) {
                $displayServers = collect($displayServers);
            }
        @endphp

        @forelse($displayServers as $serverIndex => $server)
            <div class="card border-0 shadow-sm mb-4">
                <div
                    class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center rounded-top">
                    <div class="d-flex align-items-center">
                        <div class="icon-shape bg-psti-navy text-white rounded-3 p-2 me-3">
                            <i class="fas fa-server"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-psti-navy">{{ $server->name }}</h5>
                            <small class="text-muted">{{ $server->description ?? 'Server Fisik' }}</small>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-dark border d-flex align-items-center">
                            <i class="fas fa-network-wired me-2 text-primary"></i> {{ $server->ip_address ?? 'N/A' }}
                        </span>
                        @if($server->status === 'active')
                            <span
                                class="badge bg-success bg-opacity-10 text-success border border-success d-flex align-items-center px-3">
                                <i class="fas fa-check-circle me-1"></i> Active
                            </span>
                        @else
                            <span
                                class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary d-flex align-items-center px-3">
                                <i class="fas fa-power-off me-1"></i> {{ ucfirst($server->status) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    <!-- Server Stats (Optional) -->
                    <div class="row g-0 border-bottom bg-light bg-opacity-25">
                        <div class="col-md-6 p-3 border-end">
                            <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Local Network</small>
                            <div class="fw-mono text-dark">{{ $server->local_network }}</div>
                        </div>
                        <div class="col-md-6 p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.7rem;">Total
                                        VMs</small>
                                    <div class="fw-bold text-psti-navy">{{ $server->vms->count() }} Virtual Machines</div>
                                </div>
                                <a href="{{ route('vms.create', ['server_id' => $server->id]) }}"
                                    class="btn btn-sm btn-psti-navy shadow-sm">
                                    <i class="fas fa-plus me-1"></i> Tambah VM
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- VM List -->
                    <div class="overflow-visible">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4" style="width: 5%;">#</th>
                                    <th style="width: 20%;">Nama VM</th>
                                    <th style="width: 15%;">Spesifikasi</th>
                                    <th style="width: 25%;">Resources (RAM/CPU/Disk)</th>
                                    <th style="width: 15%;">IP Address</th>
                                    <th style="width: 10%;">Status</th>
                                    <th class="text-end pe-4" style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($server->vms ?? [] as $index => $vm)
                                    <tr>
                                        <td class="ps-4 text-muted">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-light rounded text-center me-3 p-2"
                                                    style="width: 40px; height: 40px;">
                                                    <i class="fas fa-desktop text-muted"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark">{{ $vm->name }}</div>
                                                    <div class="small text-muted text-truncate" style="max-width: 150px;">
                                                        {{ $vm->description ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">
                                                {{ $vm->category->name ?? 'Standard' }}
                                            </span>
                                            <div class="small text-muted mt-1">
                                                {{ $vm->specification->name ?? 'Custom' }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info" title="RAM">
                                                    <i class="fas fa-memory me-1"></i>
                                                    {{ $vm->ram ?? ($vm->specification->ram ?? '-') }} GB
                                                </span>
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning"
                                                    title="CPU">
                                                    <i class="fas fa-microchip me-1"></i>
                                                    {{ $vm->cpu ?? ($vm->specification->cpu ?? '-') }} vCPU
                                                </span>
                                                <span
                                                    class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary"
                                                    title="Storage">
                                                    <i class="fas fa-hdd me-1"></i>
                                                    {{ $vm->storage ?? ($vm->specification->storage ?? '-') }} GB
                                                </span>
                                            </div>
                                        </td>
                                        <td class="fw-mono small text-secondary">
                                            {{ $vm->ip_address ?? '-' }}
                                        </td>
                                        <td>
                                            @php $s = $vm->status ?? 'unknown'; @endphp
                                            @if(auth()->user()->isAdmin())
                                                <button type="button" class="btn p-0 border-0 status-badge-btn"
                                                    data-vm-id="{{ $vm->id }}" data-vm-status="{{ $s }}">
                                                    @if($s === 'available')
                                                        <span class="badge bg-success rounded-pill px-3">Available</span>
                                                    @elseif($s === 'rented')
                                                        <span class="badge bg-warning text-dark rounded-pill px-3">Rented</span>
                                                    @elseif($s === 'maintenance')
                                                        <span class="badge bg-danger rounded-pill px-3">Maintenance</span>
                                                    @else
                                                        <span class="badge bg-secondary rounded-pill px-3">{{ ucfirst($s) }}</span>
                                                    @endif
                                                </button>
                                            @else
                                                @if($s === 'available')
                                                    <span class="badge bg-success rounded-pill px-3">Available</span>
                                                @elseif($s === 'rented')
                                                    <span class="badge bg-warning text-dark rounded-pill px-3">Rented</span>
                                                @else
                                                    <span class="badge bg-secondary rounded-pill px-3">{{ ucfirst($s) }}</span>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="dropdown">
                                                <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button"
                                                    data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v text-muted"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('vms.show', $vm->id) }}">
                                                            <i class="fas fa-eye me-2 text-info"></i> Detail
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('vms.edit', $vm->id) }}">
                                                            <i class="fas fa-edit me-2 text-warning"></i> Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('vms.destroy', $vm->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger"
                                                                onclick="return confirm('Yakin ingin menghapus VM ini?')">
                                                                <i class="fas fa-trash-alt me-2"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fas fa-server fa-3x mb-3 opacity-25"></i>
                                                <p class="mb-0">Belum ada Virtual Machine di server ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm text-center py-5">
                <div class="card-body">
                    <div class="icon-shape bg-light text-muted rounded-circle p-4 mb-3 mx-auto"
                        style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-server fa-2x"></i>
                    </div>
                    <h5 class="fw-bold text-muted">Belum Ada Server</h5>
                    <p class="text-muted mb-4">Silakan tambahkan server fisik terlebih dahulu melalui menu Settings.</p>
                    <a href="{{ route('servers.index') }}" class="btn btn-psti-navy">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Server
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-psti-navy text-white">
                    <h5 class="modal-title fw-bold"><i class="fas fa-sync-alt me-2"></i>Ubah Status VM</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="mb-3 text-muted">Update status operasional untuk VM: <strong id="statusModalVmName"
                            class="text-dark"></strong></p>

                    <div class="d-grid gap-2">
                        <label class="btn btn-outline-success text-start d-flex align-items-center p-3">
                            <input class="form-check-input me-3" type="radio" name="status" value="available">
                            <div>
                                <div class="fw-bold">Available</div>
                                <small>VM siap digunakan/disewa</small>
                            </div>
                        </label>

                        <label class="btn btn-outline-warning text-start d-flex align-items-center p-3">
                            <input class="form-check-input me-3" type="radio" name="status" value="rented">
                            <div>
                                <div class="fw-bold">Rented</div>
                                <small>VM sedang disewa user</small>
                            </div>
                        </label>

                        <label class="btn btn-outline-danger text-start d-flex align-items-center p-3">
                            <input class="form-check-input me-3" type="radio" name="status" value="maintenance">
                            <div>
                                <div class="fw-bold">Maintenance</div>
                                <small>VM sedang dalam perbaikan</small>
                            </div>
                        </label>

                        <label class="btn btn-outline-secondary text-start d-flex align-items-center p-3">
                            <input class="form-check-input me-3" type="radio" name="status" value="offline">
                            <div>
                                <div class="fw-bold">Offline</div>
                                <small>VM dimatikan</small>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                    <button type="button" class="btn btn-light text-muted" data-bs-dismiss="modal">Batal</button>
                    <button type="button" id="confirmStatusChangeBtn" class="btn btn-psti-navy px-4">Simpan
                        Perubahan</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusModalEl = document.getElementById('statusModal');
            if (!statusModalEl) return;
            const statusModal = new bootstrap.Modal(statusModalEl);
            let currentVmId = null;

            document.querySelectorAll('.status-badge-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    currentVmId = this.dataset.vmId;
                    const currentStatus = this.dataset.vmStatus;

                    // Set VM Name from table row
                    const row = this.closest('tr');
                    const vmName = row.querySelector('td:nth-child(2) .fw-bold').textContent;
                    document.getElementById('statusModalVmName').textContent = vmName;

                    // Select radio button
                    const radios = statusModalEl.querySelectorAll('input[name="status"]');
                    radios.forEach(r => r.checked = (r.value === currentStatus));

                    statusModal.show();
                });
            });

            document.getElementById('confirmStatusChangeBtn').addEventListener('click', function () {
                const selected = statusModalEl.querySelector('input[name="status"]:checked');
                if (!selected || !currentVmId) return;

                const newStatus = selected.value;
                const token = document.querySelector('meta[name="csrf-token"]').content;

                fetch(`/vms/${currentVmId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // Reload for simplicity to update badges
                        } else {
                            alert('Gagal update status: ' + (data.message || 'Error'));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan sistem.');
                    });
            });
        });
    </script>
@endpush