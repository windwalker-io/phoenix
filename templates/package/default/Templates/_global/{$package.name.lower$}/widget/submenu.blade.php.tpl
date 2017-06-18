<?php
/**
 * @var $helper \Windwalker\Core\View\Helper\Set\HelperSet
 * @var $router \Windwalker\Core\Router\PackageRouter
 */
?>

<h3 class="visible-xs-block">
    @translate('phoenix.title.submenu')
</h3>

<ul id="submenu" class="nav nav-pills nav-stacked">
    <li class="{{ $helper->menu->active('categories') }}">
        <a href="#">
            @translate('{$package.name.lower$}.categories.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('{$controller.list.name.lower$}') }}">
        <a href="{{ $router->route('{$controller.list.name.lower$}') }}">
            @translate('{$package.name.lower$}.{$controller.list.name.lower$}.title')
        </a>
    </li>

    {{-- @muse-placeholder  submenu  Do not remove this line --}}
</ul>
