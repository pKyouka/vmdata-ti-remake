@extends('layouts.app')

@section('title', 'Server Details')
@section('page-title', 'Server Details')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>{{ $server->name }}</h2>
            <div>
                <a href="{{ route('servers.edit', $server) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <a href="{{ route('servers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Server Information</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Name:</th>
                                <td>{{ $server->name }}</td>
                            </tr>
                            <tr>
                                <th>Local Network:</th>
                                <td><code>{{ $server->local_network }}</code></td>
                            </tr>
                            <tr>
                                <th>IP Address:</th>
                                <td>
                                    @if($server->ip_address)
                                        <code>{{ $server->ip_address }}</code>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    @if($server->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($server->status === 'maintenance')
                                        <span class="badge bg-warning">Maintenance</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Total VMs:</th>
                                <td><span class="badge bg-info">{{ $server->vms->count() }}</span></td>
                            </tr>
                            @if($server->description)
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $server->description }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Network Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Network Range:</label>
                            <div class="p-3 bg-light border rounded">
                                <code class="fs-5">{{ $server->local_network }}</code>
                            </div>
                        </div>
                        @if($server->ip_address)
                            <div class="mb-3">
                                <label class="form-label text-muted">Server IP:</label>
                                <div class="p-3 bg-light border rounded">
                                    <code class="fs-5">{{ $server->ip_address }}</code>
                                </div>
                            </div>
                        @endif
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle"></i>
                            VMs created on this server will use IP addresses from this network range.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Virtual Machines ({{ $server->vms->count() }})</h5>
                <a href="{{ route('vms.create', ['server_id' => $server->id]) }}" class="btn btn-sm btn-light">
                    <i class="fas fa-plus"></i> Add VM
                </a>
            </div>
            <div class="card-body">
                @if($server->vms->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>VM Name</th>
                                    <th>Category</th>
                                    <th>RAM (GB)</th>
                                    <th>CPU (vCPU)</th>
                                    <th>Storage (GB)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($server->vms as $vm)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong>{{ $vm->name }}</strong>
                                            @if($vm->description)
                                                <br><small class="text-muted">{{ Str::limit($vm->description, 40) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $vm->category->name ?? 'N/A' }}</td>
                                        <td>{{ $vm->ram ?? 'N/A' }}</td>
                                        <td>{{ $vm->cpu ?? 'N/A' }}</td>
                                        <td>{{ $vm->storage ?? 'N/A' }}</td>
                                        <td>
                                            @if($vm->status === 'available')
                                                <span class="badge bg-success">Available</span>
                                            @elseif($vm->status === 'rented')
                                                <span class="badge bg-warning text-dark">Rented</span>
                                            @elseif($vm->status === 'maintenance')
                                                <span class="badge" style="background-color: #ff8c00; color: #fff;">Maintenance</span>
                                            @else
                                                <span class="badge bg-secondary">Offline</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('vms.show', $vm) }}" class="btn btn-sm btn-outline-info"
                                                    title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('vms.edit', $vm) }}" class="btn btn-sm btn-outline-warning"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-server fa-3x mb-3"></i>
                        <p>No VMs on this server yet.</p>
                        <a href="{{ route('vms.create', ['server_id' => $server->id]) }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create First VM
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection