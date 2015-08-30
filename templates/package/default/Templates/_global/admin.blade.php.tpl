{{-- Part of phoenix project. --}}

@extends('_global.html')

@section('content')
<section class="jumbotron admin-header">
    <div class="container-fluid">
        <h1>{{{ \Phoenix\Html\Document::getTitle() }}}</h1>
    </div>
</section>
<aside id="admin-toolbar" class="">
    @yield('toolbar')
</aside>

<section id="admin-area">
    <div class="container-fluid">

        @section('admin-area')
        <div class="col-md-2">
            MENU
        </div>
        <div class="col-md-10">

            @section('message')
                {{ \Windwalker\Core\Widget\WidgetHelper::render('windwalker.message.default', array('flashes' => $flashes)) }}
            @show

            @yield('body', 'Body')
        </div>
        @show
    </div>
</section>

@stop
