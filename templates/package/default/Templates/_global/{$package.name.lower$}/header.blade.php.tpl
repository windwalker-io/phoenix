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

<div class="navbar navbar-default navbar-dark bg-dark navbar-fixed-top navbar-expand-lg navbar-light bg-light">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ $uri->path }}">Windwalker Phoenix</a>
        <button type="button" class="navbar-toggle navbar-toggler" data-toggle="collapse"
            data-target="#top-navbar-content" aria-controls="#top-navbar-content" aria-expanded="false">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
    <div id="top-navbar-content" class="collapse navbar-collapse">
        <ul class="nav navbar-nav mr-auto">
            @section('nav')
                @include('_global.{$package.name.lower$}.widget.mainmenu')
            @show
        </ul>
        <ul class="nav navbar-nav navbar-right">
            {{-- Add right menu here --}}
        </ul>
    </div>
    <!--/.nav-collapse -->
</div>
