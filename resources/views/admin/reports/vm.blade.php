@php $title = 'Laporan VM';
$period = $period ?? 'Semua'; @endphp
@extends('admin.reports.layout')

@section('report-content')
    <div class="mb-3">
        <strong>Ringkasan:</strong>
        <div class="row mt-2">
            <div class="col-md-3">
                <div class="card p-2">Total VM: <strong>{{ $summary['total_vms'] ?? 0 }}</strong></div>
            </div>
            <div class="col-md-3">
                <div class="card p-2">VM aktif: <strong>{{ $summary['running'] ?? 0 }}</strong></div>
            </div>
            <div class="col-md-3">
                <div class="card p-2">VM non-aktif: <strong>{{ $summary['stopped'] ?? 0 }}</strong></div>
            </div>
            <div class="col-md-3">
                <div class="card p-2">Total owner: <strong>{{ $summary['owners'] ?? 0 }}</strong></div>
            </div>
        </div>
    </div>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama VM</th>
                <th>Resource (RAM/CPU/Storage)</th>
                <th>Owner</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $vm)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $vm->name }}</td>
                    {{-- Prefer VM-specific fields when available, otherwise fall back to linked specification --}}
                    <td>
                        @php
                            $ram = $vm->ram ?? optional($vm->specification)->ram ?? null;
                            $cpu = $vm->cpu ?? optional($vm->specification)->cpu ?? null;
                            $storage = $vm->storage ?? optional($vm->specification)->storage ?? null;
                        @endphp
                        {{ $ram ?? '-' }} / {{ $cpu ?? '-' }} / {{ $storage ?? '-' }}
                    </td>
                    <td>{{ optional(optional($vm->currentRental)->user)->name ?? '-' }}</td>
                    <td>{{ ucfirst($vm->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection