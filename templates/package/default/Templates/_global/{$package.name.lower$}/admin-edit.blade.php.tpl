{{-- Part of {$package.name.cap$} project. --}}

@extends('_global.{$package.name.lower$}.admin')

@section('admin-area')
    <div class="col-md-12">

        @messages

        @yield('admin-body', 'Body')
    </div>
@stop
