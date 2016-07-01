{{-- Part of {$package.name.cap$} project. --}}

@extends('_global.{$package.name.lower$}.html')

@section('superbody')
    {{-- Force Background white i template has colored bg --}}
    <div style="background: white;">
        @yield('body', 'Body')
    </div>
@stop
