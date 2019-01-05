<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \Windwalker\Core\Package\AbstractPackage    Package object.
 * @var $view     \Windwalker\Data\Data                       Some information of this view.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $chronos  \Windwalker\Core\DateTime\Chronos           PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\PackageRouter       Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 */

/** @var \Phoenix\Html\MenuHelper $menu */
$menu = $app->make(\Phoenix\Html\MenuHelper::class);
?>
@section('nav')
    <li class="nav-item {{ $menu->active('dashboard') }}">
        <a class="nav-link" href="#">
            @lang('phoenix.title.dashboard')
        </a>
    </li>
@stop
