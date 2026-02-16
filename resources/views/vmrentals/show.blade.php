@extends('layouts.app')

@section('title', 'Detail Permintaan Sewa - VMDATA TI')
@section('page-title', 'Detail Permintaan Sewa')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="glass rounded-xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-psti-navy to-blue-900 px-6 py-4 flex justify-between items-center">
                <h3 class="text-white font-bold text-lg flex items-center">
                    <i class="fas fa-file-contract mr-2"></i> Informasi Sewa #{{ $rental->id }}
                </h3>
                @php
                    $statusColors = [
                        'active' => 'bg-green-500 text-white',
                        'pending' => 'bg-yellow-500 text-white',
                        'completed' => 'bg-gray-500 text-white',
                        'cancelled' => 'bg-red-500 text-white',
                        'expired' => 'bg-orange-500 text-white'
                    ];
                    $statusClass = $statusColors[$rental->status] ?? 'bg-blue-500 text-white';
                @endphp
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $statusClass }} shadow-sm">
                    {{ ucfirst($rental->status) }}
                </span>
            </div>

            <div class="p-6 md:p-8">
                <!-- VM & User Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h4
                            class="text-sm uppercase tracking-wide text-gray-500 font-bold mb-3 border-b border-gray-200 pb-2">
                            Informasi Umum</h4>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="w-8 flex-shrink-0 text-psti-navy"><i class="fas fa-server"></i></div>
                                <div>
                                    <span class="block text-sm text-gray-500">Virtual Machine</span>
                                    <span
                                        class="block font-medium text-gray-800">{{ $rental->vm->name ?? 'Permintaan Baru (Belum Assigned)' }}</span>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 flex-shrink-0 text-psti-navy"><i class="fab fa-linux"></i></div>
                                <div>
                                    <span class="block text-sm text-gray-500">Sistem Operasi</span>
                                    <span
                                        class="block font-medium text-gray-800">{{ $rental->operating_system ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 flex-shrink-0 text-psti-navy"><i class="fas fa-user"></i></div>
                                <div>
                                    <span class="block text-sm text-gray-500">Pemohon</span>
                                    <span
                                        class="block font-medium text-gray-800">{{ $rental->user->name ?? 'Unknown' }}</span>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 flex-shrink-0 text-psti-navy"><i class="fas fa-bullseye"></i></div>
                                <div>
                                    <span class="block text-sm text-gray-500">Tujuan Penggunaan</span>
                                    <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-100 mt-1">
                                        {{ $rental->purpose ?: '-' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4
                            class="text-sm uppercase tracking-wide text-gray-500 font-bold mb-3 border-b border-gray-200 pb-2">
                            Spesifikasi & Durasi</h4>
                        <div class="space-y-3">
                            <div class="flex items-start">
                                <div class="w-8 flex-shrink-0 text-psti-navy"><i class="fas fa-microchip"></i></div>
                                <div>
                                    <span class="block text-sm text-gray-500">CPU</span>
                                    <span
                                        class="block font-medium text-gray-800">{{ $rental->cpu ?? ($rental->vm->cpu ?? '-') }}
                                        vCPU</span>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 flex-shrink-0 text-psti-navy"><i class="fas fa-memory"></i></div>
                                <div>
                                    <span class="block text-sm text-gray-500">RAM</span>
                                    <span
                                        class="block font-medium text-gray-800">{{ $rental->ram ?? ($rental->vm->ram ?? '-') }}
                                        GB</span>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="w-8 flex-shrink-0 text-psti-navy"><i class="fas fa-hdd"></i></div>
                                <div>
                                    <span class="block text-sm text-gray-500">Storage</span>
                                    <span
                                        class="block font-medium text-gray-800">{{ $rental->storage ?? ($rental->vm->storage ?? '-') }}
                                        GB</span>
                                </div>
                            </div>
                            <div class="flex items-start mt-4">
                                <div class="w-8 flex-shrink-0 text-psti-navy"><i class="fas fa-calendar-alt"></i></div>
                                <div>
                                    <span class="block text-sm text-gray-500">Periode Sewa</span>
                                    <span class="block font-medium text-gray-800">
                                        {{ optional($rental->start_time)->format('d M Y') }} -
                                        {{ optional($rental->end_time)->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Credentials (if Active) -->
                @if($rental->vm && ($rental->status === 'active' || optional(auth()->user())->isAdmin()))
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-6">
                        <h4 class="text-blue-800 font-bold mb-3 flex items-center">
                            <i class="fas fa-key mr-2"></i> Akses Kredensial
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-blue-100">
                                <span class="text-xs text-blue-500 uppercase font-bold">Username</span>
                                <div class="font-mono text-gray-800 select-all">{{ $rental->vm->access_username ?? '-' }}
                                </div>
                            </div>
                            <div class="bg-white p-3 rounded-lg shadow-sm border border-blue-100">
                                <span class="text-xs text-blue-500 uppercase font-bold">Password</span>
                                <div class="font-mono text-gray-800 select-all">{{ $rental->vm->access_password ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-blue-600 mt-3 flex items-center">
                            <i class="fas fa-info-circle mr-1"></i>
                            Hanya Anda (pemilik sewa) dan admin yang dapat melihat informasi ini.
                        </p>
                    </div>
                @endif

                <!-- Actions -->
                @if($rental->status === 'active' && (auth()->id() === $rental->user_id || auth()->user()->isAdmin()))
                    <div class="mt-6 border-t border-gray-100 pt-6 mb-6">
                        <h4 class="text-sm uppercase tracking-wide text-gray-500 font-bold mb-4">Tindakan Lanjutan</h4>

                        @if($rental->reset_requested)
                            <div
                                class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg flex items-center shadow-sm">
                                <i class="fas fa-clock mr-3 text-lg"></i>
                                <div>
                                    <span class="font-bold block">Permintaan Reset Terkirim</span>
                                    <span class="text-sm">Admin akan memproses permintaan reset/rebuild Anda segera.</span>
                                </div>
                            </div>
                        @else
                            <div
                                class="bg-red-50 rounded-xl p-5 border border-red-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h5 class="font-bold text-red-900 flex items-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i> Request Reset / Rebuild VM
                                    </h5>
                                    <p class="text-sm text-red-700 mt-1">
                                        Gunakan fitur ini jika VM mengalami kendala teknis berat atau Anda ingin mengganti
                                        environment (OS).
                                        <strong>Perhatian:</strong> Proses ini mungkin menghapus semua data dalam VM.
                                    </p>
                                </div>
                                <form action="{{ route('vmrentals.requestReset', $rental->id) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin mengajukan reset VM? Tindakan ini akan memberitahu admin untuk melakukan reset/rebuild.');">
                                    @csrf
                                    <button type="submit"
                                        class="whitespace-nowrap px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-sm shadow-md flex items-center">
                                        <i class="fas fa-sync-alt mr-2"></i> Ajukan Reset
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-100">
                    <a href="{{ route('vmrentals.index') }}"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors shadow-sm font-medium text-sm">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>

                    @if(auth()->id() === $rental->user_id && $rental->status === 'pending')
                        <a href="{{ route('vmrentals.edit', $rental->id) }}"
                            class="px-4 py-2 bg-yellow-500 border border-transparent rounded-lg text-white hover:bg-yellow-600 transition-colors shadow-sm font-medium text-sm">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection