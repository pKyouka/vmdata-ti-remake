@extends('layouts.app')

@section('title', config('app.name', 'VMDATA TI'))

@section('content')
    {{-- This view intentionally leaves content to child views that extend 'app' --}}
    @yield('content')
@endsection
