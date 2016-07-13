{{-- Part of phoenix project. --}}

@extends('_global.{$package.name.lower$}.html')

@section('body')
    @section('header')
        @include('_global.{$package.name.lower$}.header')
    @show

    <div class="container-fluid">
        <div class="row">
            <div class="main-sidebar col-md-2">
                @include('_global.{$package.name.lower$}.widget.submenu')
            </div>
            <div class="main-body col-md-10">
                @yield('content', 'Content Section')

                @section('copyright')
                    @include('_global.{$package.name.lower$}.copyright')
                @show
            </div>
        </div>
    </div>
@stop
