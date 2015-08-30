{{-- Part of phoenix project. --}}

<a type="button" class="btn btn-success btn-sm" href="{{ $router->html('{$controller.item.name.lower$}', array('new' => true)) }}">
    <span class="glyphicon glyphicon-plus"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.new') }}
</a>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.validateChecked().patch(null, {task: 'publish'});">
    <span class="glyphicon glyphicon-ok text-success"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.publish') }}
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.validateChecked().patch(null, {task: 'unpublish'});">
    <span class="glyphicon glyphicon-remove text-danger"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.unpublish') }}
</button>

