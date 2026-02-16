@extends('layouts.app')

@section('title', 'Daftar Permintaan Sewa - VMDATA TI')
@section('page-title', 'Daftar Permintaan Sewa VM')

@section('page-actions')
    <a href="{{ route('vmrentals.create') }}"
        class="btn-primary-custom flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
        <i class="fas fa-plus-circle mr-2"></i> Buat Permintaan Baru
    </a>
@endsection

@section('content')
    <div class="glass rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr
                        class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                        <th class="px-4 py-3 text-center">#</th>
                        <th class="px-4 py-3">Informasi VM</th>
                        <th class="px-4 py-3">Pemohon</th>
                        <th class="px-4 py-3">Spesifikasi</th>
                        <th class="px-4 py-3 text-center">Periode Sewa</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($rentals as $i => $r)
                        <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                            <td class="px-4 py-3 text-center text-gray-500">
                                {{ $loop->iteration + ($rentals instanceof \Illuminate\Pagination\LengthAwarePaginator ? $rentals->firstItem() - 1 : 0) }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg mr-3">
                                        <i class="fas fa-server"></i>
                                    </div>
                                    <span class="font-medium text-gray-700">{{ $r->vm->name ?? 'Request Baru' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center">
                                    <div
                                        class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-600 mr-2">
                                        {{ substr($r->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $r->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-xs text-gray-500 space-y-1">
                                    <span class="block"><i class="fas fa-microchip w-4"></i>
                                        {{ $r->cpu ?? ($r->vm->cpu ?? '-') }} vCPU</span>
                                    <span class="block"><i class="fas fa-memory w-4"></i> {{ $r->ram ?? ($r->vm->ram ?? '-') }}
                                        GB RAM</span>
                                    <span class="block"><i class="fas fa-hdd w-4"></i>
                                        {{ $r->storage ?? ($r->vm->storage ?? '-') }} GB SSD</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="text-xs text-gray-500">
                                    <span
                                        class="block text-green-600 font-medium">{{ optional($r->start_time)->format('d M Y') }}</span>
                                    <span class="block text-gray-400 text-[10px]">s/d</span>
                                    <span
                                        class="block text-red-600 font-medium">{{ optional($r->end_time)->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-100 text-green-700 border-green-200',
                                        'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                        'completed' => 'bg-gray-100 text-gray-700 border-gray-200',
                                        'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                        'expired' => 'bg-orange-100 text-orange-700 border-orange-200'
                                    ];
                                    $statusClass = $statusColors[$r->status] ?? 'bg-blue-100 text-blue-700 border-blue-200';
                                @endphp
                                <span
                                    class="px-2 py-1 font-semibold leading-tight rounded-full border {{ $statusClass }} text-xs">
                                    {{ ucfirst($r->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('vmrentals.show', $r->id) }}"
                                        class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if(auth()->id() === $r->user_id || auth()->user()->role === 'admin')
                                        @if(in_array($r->status, ['pending']))
                                            <a href="{{ route('vmrentals.edit', $r->id) }}"
                                                class="text-yellow-500 hover:text-yellow-700 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif

                                        @if(in_array($r->status, ['pending', 'cancelled']) || auth()->user()->role === 'admin')
                                            <form action="{{ route('vmrentals.destroy', $r->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?');"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors"
                                                    title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-clipboard-list fa-3x mb-3 text-gray-300"></i>
                                    <p>Belum ada permintaan sewa.</p>
                                    <a href="{{ route('vmrentals.create') }}"
                                        class="mt-2 text-psti-navy hover:underline text-sm font-medium">Buat permintaan
                                        baru?</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $rentals->links() }}
        </div>
    </div>
@endsection