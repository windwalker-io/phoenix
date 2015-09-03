{{-- Part of phoenix project. --}}

<a type="button" class="btn btn-success btn-sm" href="{{ $router->html('{$controller.item.name.lower$}', array('new' => true)) }}">
    <span class="glyphicon glyphicon-plus"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.new') }}
</a>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked().patch(null, {task: 'publish'});">
    <span class="glyphicon glyphicon-ok text-success"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.publish') }}
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked().patch(null, {task: 'unpublish'});">
    <span class="glyphicon glyphicon-remove text-danger"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.unpublish') }}
</button>

<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#batch-modal" onclick="Phoenix.Grid.hasChecked(null, event);">
    <span class="glyphicon glyphicon-modal-window"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.batch') }}
</button>
