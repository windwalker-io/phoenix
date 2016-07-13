{{-- Part of {$package.name.cap$} project. --}}

@extends('_global.{$package.name.lower$}.admin-wrapper')

@section('content')
    @section('banner')
        @include('_global.{$package.name.lower$}.widget.banner')
    @show

    @section('toolbar')
        @include('_global.{$package.name.lower$}.widget.toolbar')
    @show

    @section('admin-area')
    <section id="admin-area">
        @messages

        @yield('admin-body', 'Admin Body')
    </section>
    @show
@stop
