{{-- Part of {$package.name.cap$} project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \Windwalker\Core\Package\AbstractPackage    Package object.
 * @var $view     \Windwalker\Data\Data                       Some information of this view.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime \Windwalker\Core\DateTime\DateTime          PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\CoreRouter          Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 */
?>

<a type="button" class="btn btn-success btn-sm" href="{{ $router->route('{$controller.item.name.lower$}', array('new' => true)) }}">
    <span class="glyphicon glyphicon-plus fa fa-plus"></span>
    @translate('phoenix.toolbar.new')
</a>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked();Phoenix.post();">
    <span class="glyphicon glyphicon-duplicate fa fa-copy text-info"></span>
    @translate('phoenix.toolbar.duplicate')
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked().batch('publish');">
    <span class="glyphicon glyphicon-ok fa fa-check text-success"></span>
    @translate('phoenix.toolbar.publish')
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked().batch('unpublish');">
    <span class="glyphicon glyphicon-remove fa fa-remove text-danger"></span>
    @translate('phoenix.toolbar.unpublish')
</button>

<button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#batch-modal" onclick="Phoenix.Grid.hasChecked(null, event);">
    <span class="glyphicon glyphicon-modal-window fa fa-sliders"></span>
    @translate('phoenix.toolbar.batch')
</button>

<button type="button" class="btn btn-default btn-sm" onclick="Phoenix.Grid.hasChecked().deleteList();">
    <span class="glyphicon glyphicon-trash fa fa-trash"></span>
    @translate('phoenix.toolbar.delete')
</button>
