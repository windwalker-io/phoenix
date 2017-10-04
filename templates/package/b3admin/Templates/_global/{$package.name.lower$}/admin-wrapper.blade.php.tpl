<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \Windwalker\Core\Package\AbstractPackage    Package object.
 * @var $view     \Windwalker\Data\Data                       Some information of this view.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime \Windwalker\Core\DateTime\Chronos           PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\PackageRouter       Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 */
?>

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
