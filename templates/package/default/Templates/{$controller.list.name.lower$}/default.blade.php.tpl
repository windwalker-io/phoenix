{{-- Part of phoenix project. --}}

@extends('_global.html')

@section('page_title')
    {$controller.list.name.cap$}
@stop

@section('content')
    <div class="container {$controller.item.name.lower$}-item">
        <h1>{$controller.item.name.cap$} List</h1>
        <p>Hello World.</p>
    </div>
@stop
