<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \Windwalker\Core\Package\AbstractPackage    Package object.
 * @var $view     \Windwalker\Data\Data                       Some information of this view.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime \Windwalker\Core\DateTime\Chronos           PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\PackageRouter       Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 */
?>

<aside id="admin-toolbar" class="">
    <button data-toggle="collapse" class="btn btn-default toolbar-toggle-button"
        data-target="#admin-toolbar-buttons">
        <span class="fa fa-wrench"></span>
        @translate('phoenix.toolbar.toggle')
    </button>
    <div id="admin-toolbar-buttons" class="admin-toolbar-buttons">
        <hr />
        @yield('toolbar-buttons')
    </div>
</aside>
