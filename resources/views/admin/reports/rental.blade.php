@php $title = 'Laporan Penyewaan VM'; $period = $period ?? 'Semua'; @endphp
@extends('admin.reports.layout')

@section('report-content')
    <div class="mb-3">
        <strong>Ringkasan:</strong>
        <div class="row mt-2">
            <div class="col-md-3"><div class="card p-2">Jumlah total sewa: <strong>{{ $summary['total_rentals'] ?? 0 }}</strong></div></div>
            <div class="col-md-3"><div class="card p-2">VM aktif saat ini: <strong>{{ $summary['active_vms'] ?? 0 }}</strong></div></div>
            <div class="col-md-3"><div class="card p-2">Selesai: <strong>{{ $summary['completed'] ?? 0 }}</strong></div></div>
            <div class="col-md-3"><div class="card p-2">Total user yang menyewa: <strong>{{ $summary['unique_users'] ?? 0 }}</strong></div></div>
        </div>
    </div>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>VM / Server</th>
                <th>Kategori</th>
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $r)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $r->user->name ?? '-' }}</td>
                    <td>{{ $r->vm->name ?? '-' }}</td>
                    <td>{{ optional($r->vm->category)->name ?? '-' }}</td>
                    <td>{{ optional($r->start_time)->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ optional($r->end_time)->format('d/m/Y') ?? '-' }}</td>
                    <td>{{ ucfirst($r->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
