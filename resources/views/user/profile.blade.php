@extends('app')

@section('title', 'VMDATA TI')
@section('page-title', 'Profil Saya')

@section('content')
<div class="container-fluid">

    <!--<h2 class="mb-4">Profil Saya</h2>-->

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}</p>
        </div>
    </div>

    <div class="mb-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Menu Pengguna</strong>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ request()->routeIs('profile.edit') ? 'active' : '' }}" title="Perbarui nama, email dan detail akun">
                    <div>
                        <i class="fas fa-user-edit me-2"></i>
                        <strong>Edit Profil</strong>
                        <div class="small text-muted">Ubah data akun</div>
                    </div>
                    <div class="text-muted"><i class="fas fa-angle-right"></i></div>
                </a>

                <a href="{{ route('profile.edit') }}#update-password" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ request()->is('profile') && Str::contains(request()->getRequestUri(),'update-password') ? 'active' : '' }}" title="Ubah kata sandi Anda">
                    <div>
                        <i class="fas fa-key me-2"></i>
                        <strong>Ubah Password</strong>
                        <div class="small text-muted">Ganti kata sandi</div>
                    </div>
                    <div class="text-muted"><i class="fas fa-angle-right"></i></div>
                </a>

                <!--<a href="{{ route('vms.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ request()->routeIs('vms.*') ? 'active' : '' }}" title="Lihat & kelola VM Anda">
                    <div>
                        <i class="fas fa-desktop me-2"></i>
                        <strong>Virtual Machines</strong>
                        <div class="small text-muted">Kelola VM</div>
                    </div>
                    <div class="text-muted"><i class="fas fa-angle-right"></i></div>
                </a>-->

                @if(Route::has('vmrentals.index'))
                    <a href="{{ route('vmrentals.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start {{ request()->routeIs('vmrentals.*') ? 'active' : '' }}" title="Lihat riwayat sewa Anda">
                        <div>
                            <i class="fas fa-file-alt me-2"></i>
                            <strong>Riwayat Sewa</strong>
                            <div class="small text-muted">Permintaan & status</div>
                        </div>
                        <div class="text-muted"><i class="fas fa-angle-right"></i></div>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="list-group-item list-group-item-action text-start d-flex justify-content-between align-items-start bg-transparent border-0">
                        <div>
                            <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                            <strong>Logout</strong>
                        </div>
                        <div class="text-muted"><i class="fas fa-angle-right"></i></div>
                    </button>
                </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            const hash = window.location.hash;
            if (hash) {
                const el = document.querySelector(hash);
                if (el) {
                    // smooth scroll to anchor
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    // optionally focus first input inside the section for accessibility
                    const input = el.querySelector('input, button, textarea, select');
                    if (input) input.focus({ preventScroll: true });
                }
            }
        } catch (e) {
            // fail silently if selector invalid
            console.debug('auto-scroll error', e);
        }
    });
</script>
@endpush
