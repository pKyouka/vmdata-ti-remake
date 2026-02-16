@extends('layouts.app')

@section('title', 'Edit Pengaturan - PSTI VM Rentals')
@section('page-title', 'Edit Pengaturan')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 fw-bold text-psti-navy">
                            <i class="fas fa-edit me-2"></i>Edit Pengaturan: <span
                                class="text-muted">{{ $setting->key }}</span>
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('admin.settings.update', $setting) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold text-muted small">Key Identifier</label>
                                <input type="text" class="form-control bg-light" value="{{ $setting->key }}" disabled>
                                <div class="form-text text-muted">Kunci pengaturan ini tidak dapat diubah.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-psti-navy">Value</label>
                                <input type="text" name="value" class="form-control"
                                    value="{{ old('value', $setting->value) }}" placeholder="Masukkan nilai pengaturan...">
                                @error('value')
                                    <div class="text-danger small mt-1"><i
                                            class="fas fa-exclamation-circle me-1"></i>{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small">Deskripsi</label>
                                <textarea class="form-control bg-light text-muted" rows="3"
                                    disabled>{{ $setting->description }}</textarea>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.settings.index') }}" class="btn btn-light border">
                                    <i class="fas fa-arrow-left me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-psti-navy">
                                    <i class="fas fa-save me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection