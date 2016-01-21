{{-- Part of {$package.name.cap$} project. --}}

@extends('_global.html')

@section('content')
<div class="container {$controller.item.name.lower$}-item">
    <h1>{$controller.item.name.cap$} Item</h1>
    <p>
        <a class="btn btn-default" href="{{ $router->html('{$controller.list.name.lower$}') }}">
            <span class="glyphicon glyphicon-chevron-left fa fa-chervon-left"></span>
            Back to List
        </a>
    </p>
    <hr />
    <h2>{{ $item->title }}</h2>
    <p>{{ $item->introtext }}</p>
    <p>{{ $item->fulltext }}</p>
</div>
@stop
