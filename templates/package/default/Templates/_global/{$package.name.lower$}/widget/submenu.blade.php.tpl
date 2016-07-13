<?php
/**
 * @var $helper \Windwalker\Core\View\Helper\Set\HelperSet
 * @var $router \Windwalker\Core\Router\PackageRouter
 */
?>

<h3 class="visible-xs-block">
    @translate('phoenix.title.submenu')
</h3>

<div id="submenu" class="list-group">
    <a href="#"
        class="list-group-item {{ $helper->menu->active('categories') }}">
        @translate('{$package.name.lower$}.categories.title')
    </a>

    <a href="{{ $router->route('{$controller.list.name.lower$}') }}"
        class="list-group-item {{ $helper->menu->active('{$controller.list.name.lower$}') }}">
        @translate('{$package.name.lower$}.{$controller.list.name.lower$}.title')
    </a>

    {{-- @muse-placeholder  submenu  Do not remove this line --}}
</div>
