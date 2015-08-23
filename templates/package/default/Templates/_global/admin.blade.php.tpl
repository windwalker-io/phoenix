{{-- Part of phoenix project. --}}

@extends('_global.html')

@section('content')
<section class="jumbotron admin-header">
    <div class="container-fluid">
        <h1>{{{ \Phoenix\Html\Document::getTitle() }}}</h1>
    </div>
</section>
<section id="admin-area">
    <div class="container-fluid">
        <div class="col-md-2">
            MENU
        </div>
        <div class="col-md-10">
            @yield('body', 'Body')
        </div>
    </div>
</section>

@stop
