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
    <button data-toggle="collapse" class="btn btn-default toolbar-toggle-button" data-target=".admin-toolbar-buttons">
        <span class="fa fa-wrench"></span>
        @translate('phoenix.toolbar.toggle')
    </button>
    <div class="admin-toolbar-buttons">
        <hr />
        @yield('toolbar-buttons')
    </div>
</aside>

@section('script')
    @parent

    <script>
        jQuery(function ($) {

            var navTop;
            var isFixed = false;
            var toolbar = $('#admin-toolbar');

            processScrollInit();
            processScroll();

            $(window).on('resize', processScrollInit);
            $(window).on('scroll', processScroll);

            function processScrollInit()
            {
                if (toolbar.length) {
                    navTop = toolbar.length && toolbar.offset().top - 30;

                    // Only apply the scrollspy when the toolbar is not collapsed
                    if (document.body.clientWidth > 480)
                    {
                        $('.subhead-collapse').height(toolbar.height());
                        toolbar.scrollspy({offset: {top: toolbar.offset().top - $('nav.navbar').height()}});
                    }
                }
            }

            function processScroll()
            {
                if (toolbar.length) {
                    var scrollTop = $(window).scrollTop();

                    if (scrollTop >= navTop && !isFixed) {
                        isFixed = true;
                        toolbar.addClass('admin-toolbar-fixed');
                    } else if (scrollTop <= navTop && isFixed) {
                        isFixed = false;
                        toolbar.removeClass('admin-toolbar-fixed');
                    }
                }
            }
        });
    </script>
@stop
