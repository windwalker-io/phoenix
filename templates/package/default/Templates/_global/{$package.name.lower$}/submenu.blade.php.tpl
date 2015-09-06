
<h3 class="visible-xs-block">
    @translate('phoenix.title.submenu')
</h3>

<ul class="nav nav-stacked nav-pills">
    <li class="{{ $helper->menu->active('categories') }}">
        <a href="#">
            @translate('{$package.name.lower$}.categories.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('{$controller.list.name.lower$}') }}">
        <a href="{{ $router->html('{$controller.list.name.lower$}') }}">
            @translate('{$package.name.lower$}.{$controller.list.name.lower$}.title')
        </a>
    </li>

    {{-- @muse-placeholder  submenu  Do not remove this line --}}
</ul>
