@extends('layouts.app')

@section('title', 'Edit Server')
@section('page-title', 'Edit Server')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Edit Server: {{ $server->name }}</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('servers.update', $server) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Server Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name', $server->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="local_network" class="form-label">Local Network (CIDR) <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('local_network') is-invalid @enderror"
                                    id="local_network" name="local_network"
                                    value="{{ old('local_network', $server->local_network) }}"
                                    placeholder="e.g., 192.168.1.0/24" required>
                                <small class="form-text text-muted">Format: IP/Subnet (e.g., 192.168.1.0/24,
                                    10.0.0.0/16)</small>
                                @error('local_network')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="ip_address" class="form-label">IP Address</label>
                                <input type="text" class="form-control @error('ip_address') is-invalid @enderror"
                                    id="ip_address" name="ip_address" value="{{ old('ip_address', $server->ip_address) }}"
                                    placeholder="e.g., 192.168.1.1">
                                <small class="form-text text-muted">Server's main IP address (optional)</small>
                                @error('ip_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                                    required>
                                    <option value="active" {{ old('status', $server->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="maintenance" {{ old('status', $server->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    <option value="inactive" {{ old('status', $server->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                    name="description" rows="3">{{ old('description', $server->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('servers.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Server
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection