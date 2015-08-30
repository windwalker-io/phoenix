{{-- Part of phoenix project. --}}

<button type="button" class="btn btn-success btn-sm" onclick="Phoenix.post();">
    <span class="glyphicon glyphicon-floppy-disk"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.save') }}
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.post(null, {task: 'save2close'});">
    <span class="glyphicon glyphicon-ok text-success"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.save2close') }}
</button>

<a type="button" class="btn btn-default btn-sm" href="{{ $router->html('{$controller.list.name.lower$}') }}">
    <span class="glyphicon glyphicon-remove text-danger"></span>
    {{ \Windwalker\Core\Language\Translator::translate('phoenix.toolbar.button.cancel') }}
</a>
