@extends('layouts.app')

@section('title', 'Admin Dashboard - VMDATA TI')
@section('page-title', 'Dashboard Overview')

@section('page-actions')
    <a href="{{ route('vms.create') }}" class="btn-primary-custom flex items-center">
        <i class="fas fa-plus mr-2"></i> New VM
    </a>
@endsection

@section('content')
    @if(optional(auth()->user())->role === 'admin')

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total VMs -->
            <div class="glass rounded-xl p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-server fa-4x text-psti-navy"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total VMs</span>
                    <span class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_vms'] }}</span>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-psti-navy font-medium flex items-center">
                        <i class="fas fa-hdd mr-1"></i> Data Center
                    </span>
                </div>
            </div>

            <!-- Active Rentals -->
            <div class="glass rounded-xl p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-clock fa-4x text-green-600"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Rentals</span>
                    <span class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['active_rentals'] }}</span>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-green-500 font-medium flex items-center">
                        <i class="fas fa-arrow-up mr-1"></i> Running
                    </span>
                </div>
            </div>

            <!-- Users -->
            <div class="glass rounded-xl p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-users fa-4x text-psti-gold"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Registered Users</span>
                    <span class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['total_users'] }}</span>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-psti-gold-dark font-medium flex items-center">
                        <i class="fas fa-user-plus mr-1"></i> Active
                    </span>
                </div>
            </div>

            <!-- Available (Calculated) -->
            <div class="glass rounded-xl p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-check-circle fa-4x text-teal-600"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Available VMs</span>
                    <span class="text-3xl font-bold text-gray-800 mt-2">{{ $stats['available_vms'] }}</span>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-teal-500 font-medium flex items-center">
                        <i class="fas fa-thumbs-up mr-1"></i> Ready to deploy
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Recent VMs List -->
            <div class="lg:col-span-2 glass rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-desktop text-psti-navy mr-2"></i> Recent VMs
                    </h3>
                    <a href="{{ route('vms.index') }}" class="text-sm link-psti-navy font-medium">View All <i
                            class="fas fa-arrow-right ml-1"></i></a>
                </div>

                <div class="space-y-4">
                    @forelse($recentVMs as $vm)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50/50 rounded-lg hover:bg-white hover:shadow-md transition-all duration-200 border border-transparent hover:border-gray-100">
                            <div class="flex items-center">
                                <div class="p-3 bg-blue-50 text-psti-navy rounded-lg mr-4">
                                    <i class="fas fa-server fa-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $vm->name }}</h4>
                                    <div class="flex items-center text-xs text-gray-500 mt-1">
                                        <span class="mr-3"><i class="fas fa-tag mr-1"></i>
                                            {{ optional($vm->category)->name ?? 'N/A' }}</span>
                                        <span><i class="fas fa-microchip mr-1"></i>
                                            {{ optional($vm->specification)->name ?? 'Standard' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $statusColors = [
                                        'available' => 'bg-green-100 text-green-700',
                                        'rented' => 'bg-yellow-100 text-yellow-700',
                                        'maintenance' => 'bg-orange-100 text-orange-700',
                                        'offline' => 'bg-gray-100 text-gray-700',
                                    ];
                                    $status = $vm->status ?? 'unknown';
                                    $colorClass = $statusColors[$status] ?? 'bg-blue-100 text-blue-700';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $colorClass }}">
                                    {{ ucfirst($status) }}
                                </span>
                                <div class="text-xs text-gray-400 mt-1">{{ $vm->ip_address ?? 'No IP' }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-server fa-3x mb-3 opacity-20"></i>
                            <p>No VMs available yet.</p>
                            <a href="{{ route('vms.create') }}" class="text-blue-500 hover:underline mt-2 inline-block">Create
                                one?</a>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Active Rentals Widget -->
            <div class="glass rounded-xl shadow-sm border border-gray-100 p-6 flex flex-col h-full">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-history text-psti-gold mr-2"></i> Active Rentals
                    </h3>
                    <a href="{{ route('rentals.index') }}" class="text-sm text-psti-gold hover:text-yellow-600 font-medium">View
                        All</a>
                </div>

                <div class="flex-1 overflow-y-auto space-y-4 max-h-[500px] custom-scrollbar pr-2">
                    @forelse($activeRentals as $rental)
                        <div
                            class="p-4 rounded-xl bg-gradient-to-br from-gray-50 to-white border border-gray-100 shadow-sm relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500"></div>
                            <div class="flex justify-between items-start mb-2">
                                <h5 class="font-bold text-gray-800">{{ $rental->vm->name }}</h5>
                                <span
                                    class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded border border-green-100">Running</span>
                            </div>

                            <div class="flex items-center mb-3">
                                <div
                                    class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 mr-2">
                                    {{ substr(optional($rental->user)->name ?? 'U', 0, 1) }}
                                </div>
                                <div class="text-sm text-gray-600 truncate max-w-[150px]">
                                    {{ optional($rental->user)->organization ?? optional($rental->user)->name ?? 'Unknown User' }}
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-2 mt-2 flex justify-between items-center text-xs text-gray-500">
                                <span><i class="fas fa-clock mr-1"></i> Ends:</span>
                                <span class="font-medium text-gray-700">
                                    {{ optional($rental->end_time)->format('M d, Y') ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">
                            <i class="fas fa-clipboard-check fa-3x mb-3 opacity-20"></i>
                            <p>No active rentals at the moment.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Quick Stats Footer -->
                <div class="mt-6 pt-4 border-t border-gray-100 grid grid-cols-2 gap-4 text-center">
                    <div>
                        <span class="block text-2xl font-bold text-gray-800">{{ $stats['rented_vms'] }}</span>
                        <span class="text-xs text-gray-500">Rented</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-bold text-gray-800">{{ $stats['maintenance_vms'] }}</span>
                        <span class="text-xs text-gray-500">Maintenance</span>
                    </div>
                </div>
            </div>
        </div>

    @else
        <div class="flex flex-col items-center justify-center h-[50vh] text-center">
            <div class="bg-red-50 p-6 rounded-2xl shadow-sm border border-red-100 max-w-lg">
                <i class="fas fa-lock fa-3x text-red-400 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Access Restricted</h3>
                <p class="text-gray-600 mb-6">You do not have permission to view the administrative dashboard.</p>
                <a href="{{ route('user.dashboard') }}" class="btn-primary-custom inline-block">
                    Return to User Dashboard
                </a>
            </div>
        </div>
    @endif
@endsection