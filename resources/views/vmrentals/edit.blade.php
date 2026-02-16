@extends('layouts.app')

@section('title', 'Edit Permintaan Sewa')
@section('page-title', 'Edit')

@section('content')
<div class="container-fluid">


    <form action="{{ route('vmrentals.update', $rental->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Pilih VM</label>
            <select name="vm_id" class="form-control">
                @foreach($vms as $vm)
                    <option value="{{ $vm->id }}" {{ $rental->vm_id == $vm->id ? 'selected' : '' }}>{{ $vm->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">CPU (vCPU)</label>
                <input type="number" name="cpu" min="1" step="1" class="form-control" value="{{ old('cpu', $rental->cpu ?? 1) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">RAM (GB)</label>
                <input type="number" name="ram" min="1" class="form-control" value="{{ old('ram', $rental->ram) }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Storage (GB)</label>
                <input type="number" name="storage" min="1" class="form-control" value="{{ old('storage', $rental->storage) }}" required>
            </div>
            
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Mulai</label>
                <input type="date" name="start_time" value="{{ optional($rental->start_time)->format('Y-m-d') }}" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Selesai</label>
                <input type="date" name="end_time" value="{{ optional($rental->end_time)->format('Y-m-d') }}" class="form-control">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Tujuan</label>
            <textarea name="purpose" class="form-control">{{ $rental->purpose }}</textarea>
        </div>
        <button class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
