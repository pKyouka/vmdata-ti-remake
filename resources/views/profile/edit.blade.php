@extends('layouts.app')
@section('title', 'Profil')
@section('page-title', 'Pengaturan Akun')
@section('content')
<div class="container">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <h3>Profil</h3>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="mb-2">
            <label>Nama</label>
            <input name="name" value="{{ old('name', $user->name) }}" class="form-control"/>
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-2">
            <label>Email</label>
            <input name="email" value="{{ old('email', $user->email) }}" class="form-control"/>
            @error('email') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button class="btn btn-primary">Simpan</button>
    </form>

    <hr/>

    <h4>Ubah Password</h4>
    <form method="POST" action="{{ route('profile.password') }}">
        @csrf
        @method('PATCH')

        <div class="mb-2">
            <label>Password Saat Ini</label>
            <input type="password" name="current_password" class="form-control"/>
            @error('current_password') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-2">
            <label>Password Baru</label>
            <input type="password" name="password" class="form-control"/>
            @error('password') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-2">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control"/>
        </div>

        <button class="btn btn-warning">Ubah Password</button>
    </form>

    <hr/>

    <h4>Avatar</h4>
    <img id="avatarPreview" src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('images/default-avatar.png') }}" alt="avatar" width="120" class="mb-2"/>
    <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
            <input id="avatarInput" type="file" name="avatar" accept="image/*" class="form-control"/>
            <div id="avatarInfo" class="form-text text-muted mt-1">Max size: 10 MB. Allowed types: image/*</div>
            <div id="avatarError" class="text-danger mt-1" style="display:none;"></div>
            @error('avatar') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-info">Upload Avatar</button>
    </form>

    <hr/>

    <h4>Hapus Akun</h4>
    <form method="POST" action="{{ route('profile.destroy') }}">
        @csrf
        @method('DELETE')
        <div class="mb-2">
            <label>Masukkan password untuk konfirmasi</label>
            <input type="password" name="password" class="form-control"/>
            @error('password') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
        <button class="btn btn-danger">Hapus Akun</button>
    </form>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatarInput');
    const preview = document.getElementById('avatarPreview');
    const info = document.getElementById('avatarInfo');
    const error = document.getElementById('avatarError');
    if (!input) return;

    const MAX_BYTES = 10 * 1024 * 1024; // 10 MB

    input.addEventListener('change', function (e) {
        error.style.display = 'none';
        error.textContent = '';
        const file = this.files && this.files[0];
        if (!file) return;

        if (!file.type.startsWith('image/')) {
            error.style.display = '';
            error.textContent = 'File harus berupa gambar (image).';
            this.value = '';
            return;
        }

        if (file.size > MAX_BYTES) {
            error.style.display = '';
            error.textContent = 'Ukuran file terlalu besar. Maksimum 10 MB.';
            this.value = '';
            return;
        }

        // show size and filename
        const kb = Math.round(file.size / 1024);
        info.textContent = `Pilih file: ${file.name} — ${kb} KB (maks 10240 KB)`;

        const reader = new FileReader();
        reader.onload = function (ev) {
            preview.src = ev.target.result;
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush

@endsection
