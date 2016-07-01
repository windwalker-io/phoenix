{{-- Part of {$package.name.cap$} project. --}}

@extends('_global.{$package.name.lower$}.admin-wrapper')

@section('content')
    @include('_global.{$package.name.lower$}.widget.banner')

    @include('_global.{$package.name.lower$}.widget.toolbar')

    @section('admin-area')
    <section id="admin-area">
        @messages

        @yield('admin-body', 'Admin Body')
    </section>
    @show
@stop
