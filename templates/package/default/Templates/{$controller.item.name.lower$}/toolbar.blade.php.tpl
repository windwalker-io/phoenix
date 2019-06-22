{{-- Part of {$package.name.cap$} project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \{$package.namespace$}{$package.name.cap$}\{$package.name.cap$}Package                 Package object.
 * @var $view     \{$package.namespace$}{$package.name.cap$}\View\{$controller.item.name.cap$}\{$controller.item.name.cap$}HtmlView    View object.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $chronos  \Windwalker\Core\DateTime\DateTime          PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\MainRouter          Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 */
?>

<div class="btn-group phoenix-btn-save-dropdown">
    <button type="button" class="btn btn-success btn-sm btn-wide phoenix-btn-save"
        onclick="Phoenix.post();">
        <span class="fa fa-save"></span>
        @lang('phoenix.toolbar.save')
    </button>
    <button type="button" class="btn btn-success btn-sm dropdown-toggle dropdown-toggle-split"
        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>

    <ul class="dropdown-menu dropdown-menu-right">
        <li>
            <a class="dropdown-item phoenix-btn-save2copy"
                href="javascript://"
                onclick="Phoenix.post(null, {task: 'save2copy'});">
                <span class="fa fa-fw fa-copy text-info"></span>
                @lang('phoenix.toolbar.save2copy')
            </a>
        </li>

        <li>
            <a class="dropdown-item phoenix-btn-save2new"
                href="javascript://"
                onclick="Phoenix.post(null, {task: 'save2new'});">
                <span class="fa fa-fw fa-plus text-primary"></span>
                @lang('phoenix.toolbar.save2new')
            </a>
        </li>
    </ul>
</div>

<button type="button" class="btn btn-primary btn-sm phoenix-btn-save2close"
    onclick="Phoenix.post(null, {task: 'save2close'});">
    <span class="fa fa-check"></span>
    @lang('phoenix.toolbar.save2close')
</button>

<a role="button" class="btn btn-default btn-outline-secondary btn-sm phoenix-btn-cancel"
    href="{{ $router->to('{$controller.list.name.lower$}') }}">
    <span class="fa fa-remove fa-times"></span>
    @lang('phoenix.toolbar.cancel')
</a>
