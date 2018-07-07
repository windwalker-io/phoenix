{{-- Part of {$package.name.cap$} project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app           \Windwalker\Web\Application                 Global Application
 * @var $package       \{$package.namespace$}{$package.name.cap$}\{$package.name.cap$}Package                   Package object.
 * @var $view          \Windwalker\Data\Data                       Some information of this view.
 * @var $uri           \Windwalker\Uri\UriData               Uri information, example: $uri->path
 * @var $datetime      \Windwalker\Core\DateTime\Chronos           PHP DateTime object of current time.
 * @var $helper        \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router        \Windwalker\Core\Router\PackageRouter       Router object.
 * @var $asset         \Windwalker\Core\Asset\AssetManager         The Asset manager.
 *
 * View variables
 * --------------------------------------------------------------
 * @var $state         \Windwalker\Structure\Structure
 * @var $items         \Windwalker\Data\DataSet
 * @var $item          \{$package.namespace$}{$package.name.cap$}\Record\{$controller.item.name.cap$}Record
 * @var $pagination    \Windwalker\Core\Pagination\Pagination
 */
?>

@extends('_global.html')

@push('script')
    {{-- Add Script Here --}}
@endpush

@section('content')
    <div class="container {$controller.item.name.lower$}-list">
        <h1>{$controller.item.name.cap$} List</h1>
        <div class="{$controller.list.name.lower$}-items">
            @foreach ($items as $i => $item)
                <div class="{$controller.item.name.lower$}-item">
                    <p>
                        <span class="fa fa-angle-right text-muted"></span>
                        <a href="{{ $router->route('{$controller.item.name.lower$}', ['id' => $item->id]) }}">
                            {{ $item->title }}
                        </a>
                    </p>
                </div>
            @endforeach
        </div>
        <hr />
        <div class="pagination">
            {!! $pagination->route('{$controller.list.name.lower$}', [])->render() !!}
        </div>
    </div>
@stop
