@extends('layouts.app')

@section('title', 'System Settings - PSTI VM Rentals')
@section('page-title', 'System Settings')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row g-4">
            <!-- Server Management Card -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4 text-center">
                        <div class="icon-shape bg-psti-navy text-white rounded-circle p-3 mb-3 mx-auto"
                            style="width: 64px; height: 64px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-server fa-2x"></i>
                        </div>
                        <h5 class="fw-bold mb-2 text-psti-navy">Server Management</h5>
                        <p class="text-muted small mb-4">
                            Konfigurasi server fisik, alokasi resource pool, dan monitoring status node.
                        </p>
                        <a href="{{ route('servers.index') }}" class="btn btn-outline-psti-navy w-100">
                            <i class="fas fa-cog me-2"></i>Manage Servers
                        </a>
                    </div>
                </div>
            </div>

            <!-- Application Configuration -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-psti-navy">
                            <i class="fas fa-sliders-h me-2"></i>Application Configuration
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Key</th>
                                        <th>Value</th>
                                        <th>Description</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($settings as $s)
                                        <tr>
                                            <td class="ps-4 fw-semibold text-psti-navy">{{ $s->key }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-light text-dark border">{{ Str::limit($s->value, 30) }}</span>
                                            </td>
                                            <td class="text-muted small">{{ $s->description }}</td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('admin.settings.edit', $s) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fa-2x mb-2 opacity-25"></i>
                                                <p class="mb-0">Tidak ada konfigurasi tambahan.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-outline-psti-navy {
            color: var(--psti-navy);
            border-color: var(--psti-navy);
        }

        .btn-outline-psti-navy:hover {
            background-color: var(--psti-navy);
            color: white;
        }
    </style>
@endsection