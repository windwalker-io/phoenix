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

<h3 class="visible-xs-block d-sm-block d-md-none">
    @translate('phoenix.title.submenu')
</h3>

<ul id="submenu" class="nav nav-stacked nav-pills flex-column">
    <li class="{{ $helper->menu->active('categories') }}">
        <a href="#" class="nav-link {{ $helper->menu->active('categories') }}">
            Example Item
        </a>
    </li>

    <li class="{{ $helper->menu->active('{$controller.list.name.lower$}') }}">
        <a href="{{ $router->route('{$controller.list.name.lower$}') }}" class="nav-link {{ $helper->menu->active('{$controller.list.name.lower$}') }}">
            @translate('{$package.name.lower$}.{$controller.list.name.lower$}.title')
        </a>
    </li>

    {{-- @muse-placeholder  submenu  Do not remove this line --}}
</ul>
