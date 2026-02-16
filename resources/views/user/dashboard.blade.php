@extends('layouts.app')

@section('title', 'Dashboard Pengguna - VMDATA TI')
@section('page-title', 'Dashboard Pengguna')

@section('page-actions')
    <a href="{{ route('vmrentals.create') }}"
        class="btn-primary-custom flex items-center shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300">
        <i class="fas fa-plus-circle mr-2"></i> Buat Permintaan Sewa
    </a>
@endsection

@section('content')
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- My VMs -->
        <div class="glass rounded-xl p-6 relative overflow-hidden group border border-white/20">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-server fa-4x text-psti-navy"></i>
            </div>
            <div class="flex flex-col relative z-10">
                <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Jumlah VM Saya</span>
                <span class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['my_vms'] ?? 0 }}</span>
            </div>
            <div class="mt-4 flex items-center text-sm relative z-10">
                <span class="text-psti-navy font-medium flex items-center bg-blue-50 px-2 py-1 rounded-md">
                    <i class="fas fa-hdd mr-1"></i> Terdaftar
                </span>
            </div>
        </div>

        <!-- Active Rentals -->
        <div class="glass rounded-xl p-6 relative overflow-hidden group border border-white/20">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-clock fa-4x text-green-600"></i>
            </div>
            <div class="flex flex-col relative z-10">
                <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sewa Aktif</span>
                <span class="text-4xl font-bold text-gray-800 mt-2">{{ $stats['active_rentals'] ?? 0 }}</span>
            </div>
            <div class="mt-4 flex items-center text-sm relative z-10">
                <span class="text-green-600 font-medium flex items-center bg-green-50 px-2 py-1 rounded-md">
                    <i class="fas fa-check-circle mr-1"></i> Running
                </span>
            </div>
        </div>

        <!-- Total Spent (Optional/Placeholder) -->
        <div class="glass rounded-xl p-6 relative overflow-hidden group border border-white/20">
            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i class="fas fa-wallet fa-4x text-psti-gold"></i>
            </div>
            <div class="flex flex-col relative z-10">
                <span class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Belanja</span>
                <span class="text-4xl font-bold text-gray-800 mt-2">Rp
                    {{ number_format($stats['total_spent'] ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="mt-4 flex items-center text-sm relative z-10">
                <span class="text-psti-gold-dark font-medium flex items-center bg-orange-50 px-2 py-1 rounded-md">
                    <i class="fas fa-coins mr-1"></i> Tagihan
                </span>
            </div>
        </div>
    </div>

    <!-- My Rentals List -->
    <div class="glass rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-list-ul text-psti-navy mr-3"></i> Daftar VM yang Saya Sewa
            </h3>

        </div>

        @include('user.partials.my_rentals')
    </div>
@endsection