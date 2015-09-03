
<ul class="nav nav-stacked nav-pills">
    <li class="{{ $helper->menu->active('categories') }}">
        <a href="#">Categories</a>
    </li>

    <li class="{{ $helper->menu->active('{$controller.list.name.lower$}') }}">
        <a href="{{ $router->html('{$controller.list.name.lower$}') }}">{$controller.list.name.cap$}</a>
    </li>
</ul>
