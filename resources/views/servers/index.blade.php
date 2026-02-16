@extends('layouts.app')

@section('title', 'Manage Servers')
@section('page-title', 'Server Management')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Servers</h2>
            <a href="{{ route('servers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Server
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Local Network</th>
                                <th>IP Address</th>
                                <th>VMs Count</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($servers as $server)
                                <tr>
                                    <td>{{ $loop->iteration + ($servers->currentPage() - 1) * $servers->perPage() }}</td>
                                    <td>
                                        <strong>{{ $server->name }}</strong>
                                        @if($server->description)
                                            <br><small class="text-muted">{{ Str::limit($server->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $server->local_network }}</code></td>
                                    <td>
                                        @if($server->ip_address)
                                            <code>{{ $server->ip_address }}</code>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $server->vms_count }} VMs</span>
                                    </td>
                                    <td>
                                        @if($server->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($server->status === 'maintenance')
                                            <span class="badge bg-warning">Maintenance</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('servers.show', $server) }}" class="btn btn-sm btn-outline-info"
                                                title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('servers.edit', $server) }}"
                                                class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('servers.destroy', $server) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Are you sure you want to delete this server?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-server fa-3x mb-3"></i>
                                        <p>No servers found. Create one to get started!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $servers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection