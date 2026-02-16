@auth
    @if(optional(Auth::user())->role === 'admin')
        @include('layouts.partials.sidebar-admin')
    @else
        @include('layouts.partials.sidebar-user')
    @endif
@else
    <div class="p-4">
        <a href="{{ route('login') }}" class="block px-4 py-2 rounded bg-blue-600 text-white text-center">Login</a>
    </div>
@endauth
