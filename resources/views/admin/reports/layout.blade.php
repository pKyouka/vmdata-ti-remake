@extends('layouts.app')

@section('title', $title ?? 'Laporan - VMDATA TI')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="mb-0">{{ config('app.name', 'VMDATA TI') }}</h4>
                    <small class="text-muted">{{ $subtitle ?? 'Sistem Manajemen VM' }}</small>
                </div>
                <div class="text-end">
                    <h5 class="mb-0">{{ $title ?? 'Laporan' }}</h5>
                    <small class="text-muted">Periode: {{ $period ?? '-' }}</small>
                </div>
            </div>

            <hr>

            {{-- Report parameters area (optional) --}}
            @if(isset($filters))
                <div class="mb-3">
                    <strong>Filter:</strong>
                    <div class="small text-muted">{!! $filters !!}</div>
                </div>
            @endif

            {{-- Content slot for specific report body --}}
            <div class="report-body">
                @yield('report-content')
            </div>

            <hr>
            <div class="d-flex justify-content-between">
                <div class="small text-muted">Laporan ini dibuat otomatis oleh sistem VMDATA TI</div>
                <div class="small text-muted">Dicetak: {{ now()->format('Y-m-d') }} oleh {{ optional(auth()->user())->name ?? 'System' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
