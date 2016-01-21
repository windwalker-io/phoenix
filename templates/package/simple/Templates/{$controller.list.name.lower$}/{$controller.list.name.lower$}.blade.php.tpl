{{-- Part of phoenix project. --}}

@extends('_global.html')

@section('content')
<div class="container {$controller.item.name.lower$}-item">
    <h1>{$controller.item.name.cap$} List</h1>
    <p>Hello World.</p>
    <div class="{$controller.list.name.lower$}-items">
        @foreach ($items as $item)
        <div class="{$controller.item.name.lower$}-item">
            <p>
                <span class="glyphicon glyphicon-menu-right fa fa-angle-right text-muted"></span>
                <a href="{{ $router->html('{$controller.item.name.lower$}', array('id' => $item->id)) }}">
                    {{ $item->title }}
                </a>
            </p>
        </div>
        @endforeach
    </div>
</div>
@stop
