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
?><!DOCTYPE html>
<html lang="{{ $app->get('language.locale') ? : $app->get('language.default', 'en-GB') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">

    <title>{{ \Phoenix\Html\HtmlHeader::getPageTitle() }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ $asset->path }}/images/favicon.ico" />
    <meta name="generator" content="Windwalker Framework" />
    {!! \Phoenix\Html\HtmlHeader::renderMetadata() !!}
    @yield('meta')

    {!! $asset->renderStyles(true) !!}
    @yield('style')

    {!! $asset->renderScripts(true) !!}
    @yield('script')
    {!! \Phoenix\Html\HtmlHeader::renderCustomTags() !!}
</head>
<body class="package-{{ $package->name }} view-{{ strtolower($view->getName()) }} layout-{{ $view->getLayout() }}" style="padding-top: 50px">
    @section('navbar')
    <div class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ $uri->path }}">Windwalker</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                     @section('nav')
                        <li class="active"><a href="{{ $uri->path }}">Home</a></li>
                     @show
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    {{-- <li class="pull-right"><a href="{{ $uri->path }}/admin">Admin</a></li> --}}
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
    @show

    @section('message')
        @messages()
    @show

    @yield('content', 'Content')

    @section('copyright')
    <div id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <hr />

                    <footer>
                        &copy; Windwalker {{ $datetime->format('Y') }}
                    </footer>
                </div>
            </div>
        </div>
    </div>
    @show
{!! $asset->getTemplate()->renderTemplates() !!}
</body>
</html>
