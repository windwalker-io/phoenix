{{-- Part of phoenix project. --}}

<button type="button" class="btn btn-success btn-sm">
    <span class="glyphicon glyphicon-plus"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.new') }}
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.patch(null, {task: 'publish'});">
    <span class="glyphicon glyphicon-ok text-success"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.publish') }}
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.patch(null, {task: 'unpublish'});">
    <span class="glyphicon glyphicon-remove text-danger"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.unpublish') }}
</button>

