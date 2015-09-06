
<h3 class="visible-xs-block">
    @translate('phoenix.title.submenu')
</h3>

<ul class="nav nav-stacked nav-pills">
    <li class="{{ $helper->menu->active('categories') }}">
        <a href="#">
            @translate('{$package.name.lower$}.categories')
        </a>
    </li>

    @foreach ($helper->menu->getSubmenus() as $menu)
        <li class="{{ $helper->menu->active('{$controller.list.name.lower$}') }}">
            {{ $menu }}
        </li>
    @endforeach
</ul>
