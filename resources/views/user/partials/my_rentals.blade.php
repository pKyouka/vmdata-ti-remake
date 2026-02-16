@php $me = auth()->user(); @endphp

<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="text-xs font-semibold tracking-wide text-gray-500 uppercase border-b border-gray-200 bg-gray-50">
                <th class="px-4 py-3 text-center">#</th>
                <th class="px-4 py-3">Nama VM</th>
                <th class="px-4 py-3 text-center">Periode Sewa</th>
                <th class="px-4 py-3 text-center">Status</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            @forelse($myRentals as $i => $rental)
                <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                    <td class="px-4 py-3 text-center text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg mr-3">
                                <i class="fas fa-server"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-700 text-sm">{{ $rental->vm->name ?? 'N/A' }}</p>
                                @if(isset($rental->vm->description))
                                    <p class="text-xs text-gray-500 truncate max-w-[200px]">{{ $rental->vm->description }}</p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="text-xs">
                            @php
                                $hasStartTime = isset($rental->start_time) && $rental->start_time;
                                $hasEndTime = isset($rental->end_time) && $rental->end_time;
                            @endphp
                            <div class="flex flex-col space-y-1">
                                <span class="text-gray-600">
                                    <i class="fas fa-play-circle text-green-500 mr-1"></i>
                                    @if($hasStartTime)
                                        {{ optional($rental->start_time)->format('d M Y') }}
                                    @else
                                        {{ optional($rental->start_date)->format('d M Y') ?? '-' }}
                                    @endif
                                </span>
                                <span class="text-gray-500">
                                    <i class="fas fa-stop-circle text-red-500 mr-1"></i>
                                    @if($hasEndTime)
                                        {{ optional($rental->end_time)->format('d M Y') }}
                                    @else
                                        {{ optional($rental->end_date)->format('d M Y') ?? '-' }}
                                    @endif
                                </span>
                            </div>
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
                            $statusClass = $statusColors[$rental->status] ?? 'bg-blue-100 text-blue-700 border-blue-200';
                        @endphp
                        <span class="px-2 py-1 font-semibold leading-tight rounded-full border {{ $statusClass }} text-xs">
                            {{ ucfirst($rental->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('vmrentals.show', $rental->id) }}"
                                class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors"
                                title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($rental->status === 'pending')
                                <a href="{{ route('vmrentals.edit', $rental->id) }}"
                                    class="text-yellow-500 hover:text-yellow-700 bg-yellow-50 hover:bg-yellow-100 p-2 rounded-lg transition-colors"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                            @if(in_array($rental->status, ['pending', 'cancelled']))
                                <form method="POST" action="{{ route('vmrentals.destroy', $rental->id) }}"
                                    onsubmit="return confirm('Yakin ingin menghapus permintaan sewa ini?');"
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
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-server fa-3x mb-3 text-gray-300"></i>
                            <p>Anda belum menyewa VM apapun.</p>
                            <a href="{{ route('vmrentals.create') }}"
                                class="mt-2 text-psti-navy hover:underline text-sm font-medium">Buat permintaan baru?</a>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>