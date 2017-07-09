{{-- Part of {$package.name.cap$} project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \{$package.namespace$}{$package.name.cap$}\{$package.name.cap$}Package                 Package object.
 * @var $view     \{$package.namespace$}{$package.name.cap$}\View\{$controller.list.name.cap$}\{$controller.list.name.cap$}HtmlView  View object.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime \Windwalker\Core\DateTime\DateTime          PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\MainRouter          Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 */
?>

<a type="button" class="btn btn-success btn-sm" href="{{ $router->route('{$controller.item.name.lower$}', ['new' => true]) }}">
    <span class="fa fa-plus"></span>
    @translate('phoenix.toolbar.new')
</a>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked();Phoenix.post();">
    <span class="fa fa-copy text-info"></span>
    @translate('phoenix.toolbar.duplicate')
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked().batch('publish');">
    <span class="fa fa-check text-success"></span>
    @translate('phoenix.toolbar.publish')
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked().batch('unpublish');">
    <span class="fa fa-remove text-danger"></span>
    @translate('phoenix.toolbar.unpublish')
</button>

<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#batch-modal" onclick="Phoenix.Grid.hasChecked(null, event);">
    <span class="fa fa-sliders"></span>
    @translate('phoenix.toolbar.batch')
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked().deleteList();">
    <span class="fa fa-trash"></span>
    @translate('phoenix.toolbar.delete')
</button>
