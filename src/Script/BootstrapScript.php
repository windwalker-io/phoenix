<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Widget\WidgetHelper;
use Windwalker\Utilities\Arr;

/**
 * The BootstrapScript class.
 * @since  1.0
 */
abstract class BootstrapScript extends AbstractPhoenixScript
{
    const GLYPHICONS = 'glyphicons';

    const FONTAWESOME = 'fontawesome';

    /**
     * Property currentVersion.
     *
     * @var  int
     */
    public static $currentVersion = 3;

    /**
     * Property faVersion.
     *
     * @var  int
     */
    public static $faVersion = 4;

    /**
     * Property faPackage.
     *
     * @var  string
     */
    public static $faFont = 'FontAwesome';

    /**
     * css
     *
     * @param int $version
     *
     * @return void
     */
    public static function css($version = 3)
    {
        if (!static::inited(__METHOD__)) {
            static::$currentVersion = (int) $version;

            if ($version == 3) {
                static::addCSS(static::phoenixName() . '/css/bootstrap/bootstrap.min.css');
            } else {
                static::addCSS(static::phoenixName() . '/css/bootstrap/4/bootstrap.min.css');
            }
        }
    }

    /**
     * script
     *
     * @param int $version
     *
     * @return void
     */
    public static function script($version = 3)
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();

            if ($version == 3) {
                static::addJS(static::phoenixName() . '/js/bootstrap/bootstrap.min.js');
            } else {
                static::addJS(static::phoenixName() . '/js/bootstrap/4/bootstrap.bundle.min.js');
            }
        }
    }

    /**
     * tooltip
     *
     * @param string $selector
     * @param array  $options
     *
     * @return  void
     */
    public static function tooltip($selector = '.hasTooltip, .has-tooltip', array $options = [])
    {
        if (!static::inited(__METHOD__)) {
            static::script();
        }

        if (!static::inited(__METHOD__, func_get_args())) {
            $optionsString = json_encode(
                static::mergeOptions(
                    [
                        'boundary' => 'window'
                    ],
                    $options
                )
            );

            PhoenixScript::domready("$('{$selector}').tooltip($optionsString);");
        }
    }

    /**
     * checkbox
     *
     * @param string $iconSet
     */
    public static function checkbox($iconSet = self::GLYPHICONS)
    {
        if (!static::inited(__METHOD__)) {
            $asset = static::getAsset();

            $asset->addStyle(static::phoenixName() . '/css/bootstrap/awesome-checkbox.min.css');

            $font    = 'Glyphicons Halflings';
            $content = '\\e013';
            $weight  = '"normal"';

            if ($iconSet === static::FONTAWESOME) {
                $content = '\\f00c';
                $font    = static::$faFont;
                $weight  = static::$faVersion === 4 ? $weight : 900;
            }

            $css = <<<CSS
/* Bootstrap Awesome Checkbox */
.checkbox input[type=checkbox]:checked + label:after {
  font-family: '$font';
  content: "$content";
  font-weight: $weight;
}
.checkbox label:after {
  font-size: 9px;
  margin-top: .2em;
}
.checkbox label::before {
  margin-top: .2em;
}
.checkbox input {
  cursor: pointer;
}
.checkbox.single-checkbox {
  margin: 0;
  padding: 0;
  width: 21px;
}
.checkbox.single-checkbox label {
  padding: 0;
}
.checkbox.single-checkbox label::before,
.checkbox.single-checkbox label::after,
.checkbox.single-checkbox input[type=checkbox] {
  margin: 0;
}
.checkbox.single-checkbox label::after {
  padding-left: 2px;
  padding-top: 0;
}
.checkbox.single-checkbox input[type=checkbox] {
  width: 17px;
  height: 17px;
  display: none;
}
CSS;

            $asset->internalStyle(
                WINDWALKER_DEBUG ? $css : str_replace(["\n", "\r"], '', $css)
            );
        }
    }

    /**
     * modal
     *
     * @param string $selector
     * @param array  $options
     *
     * @return  void
     */
    public static function modal(
        string $selector = '.hasModal, .has-modal, [data-toggle=modal-link]',
        array $options = []
    ): void {
        if (!static::inited(__METHOD__)) {
            static::script();
            PhoenixScript::phoenix();

            static::addJS(static::phoenixName() . '/js/bootstrap/modal-link.min.js');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $id = $options['id'] ?? 'phoenix-iframe-modal';

            $jsOptions = static::getJSObject(
                [
                    'size' => $options['size'] ?? 'modal-xl',
                    'resize' => $options['auto_size'] ?? null
                ]
            );

            $js = <<<JS
// Modal task
$('{$selector}').modalLink('#$id', $jsOptions);
JS;

            PhoenixScript::domready($js);

            Asset::getTemplate()->addTemplate(
                'phoenix.iframe.modal.' . $id,
                WidgetHelper::render(
                    $options['template'] ?? 'phoenix.bootstrap.modal.iframe',
                    array_merge(
                        compact('id'),
                        $options['data'] ?? []
                    ),
                    $options['engine'] ?? 'edge'
                )
            );
        }
    }

    /**
     * calendar
     *
     * @param string $selector
     * @param string $format
     * @param array  $options
     *
     * @return  void
     */
    public static function calendar($selector = '.hasCalendar', $format = 'YYYY-MM-DD HH:mm:ss', $options = [])
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();

            CoreScript::moment(false, Translator::getLocale());
            static::addJS(static::phoenixName() . '/js/bootstrap/bootstrap-datetimepicker.min.js');

            if (static::$currentVersion === 3) {
                static::addCSS(static::phoenixName() . '/css/bootstrap/bootstrap-datetimepicker.min.css');
            } else {
                static::addCSS(static::phoenixName() . '/css/bootstrap/bootstrap4-datetimepicker.min.css');
            }
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $defaultOptions = [
                'debug' => (bool) WINDWALKER_DEBUG,
                'format' => $format,
                'sideBySide' => true,
                'calendarWeeks' => true,

                // Totally support font-awesome now
                // @see https://github.com/smalot/bootstrap-datetimepicker/issues/160
                'icons' => [
                    'time' => 'fa fa-clock-o',
                    'date' => 'fa fa-calendar',
                    'up' => 'fa fa-chevron-up',
                    'down' => 'fa fa-chevron-down',
                    'previous' => 'fa fa-chevron-left',
                    'next' => 'fa fa-chevron-right',
                    'today' => 'fa fa-calendar-check-o',
                    'clear' => 'fa fa-trash-o',
                    'close' => 'fa fa-close',
                ],
            ];

            $options = static::getJSObject($defaultOptions, $options);

            $js = <<<JS
$('$selector').datetimepicker($options).on('dp.change', function (event) {
    $(this).find('input').trigger('change');
});
JS;

            PhoenixScript::domready($js);
        }
    }

    /**
     * tabState
     *
     * @param string $selector
     * @param int    $time
     */
    public static function tabState($selector = '#admin-form', $time = 30)
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();

            static::addJS(static::phoenixName() . '/js/bootstrap/tab-state.min.js');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $time = (int) $time;

            PhoenixScript::domready("new LoadTab($('$selector'), $time);");
        }
    }

    /**
     * buttonRadio
     *
     * @param string $selector
     * @param array  $options
     */
    public static function buttonRadio($selector = '#admin-form', array $options = [])
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();

            static::addJS(static::phoenixName() . '/js/bootstrap/button-radio.min.js');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $options = static::getJSObject($options);

            PhoenixScript::domready("$('$selector').buttonRadio($options);");
        }
    }

    /**
     * fontAwesome
     *
     * @param int   $version
     * @param array $options
     *
     * @return  void
     */
    public static function fontAwesome($version = 4, array $options = [])
    {
        if (!static::inited(__METHOD__)) {
            static::$faVersion = (int) $version;

            if ($version === 5) {
                static::$faFont = 'Font Awesome 5 Free';
                static::addCSS(static::phoenixName() . '/css/fontawesome/all.min.css');

                if (!empty($options['v4shims'])) {
                    static::addCSS(static::phoenixName() . '/css/fontawesome/v4-shims.min.css');
                }

                if (!empty($options['pro'])) {
                    static::$faFont = 'Font Awesome 5 Pro';
                }

                // TODO: Make sure FA fix this then remove.
                static::internalCSS(".fa.fab { font-family: 'Font Awesome 5 Brands'; }");
            } else {
                static::addCSS(static::phoenixName() . '/css/font-awesome.min.css');
                static::$faFont = 'FontAwesome';
            }
        }
    }

    /**
     * switcher
     *
     * @return  void
     *
     * @since  1.6.2
     */
    public static function switcher()
    {
        if (!static::inited(__METHOD__)) {
            static::addCSS(static::phoenixName() . '/css/bootstrap/switch.min.css');
        }
    }

    /**
     * multiLevelDropdown
     *
     * @return  void
     *
     * @deprecated Use multiLevelMenu()
     *
     * @since      1.8.13
     */
    public static function multiLevelDropdown(): void
    {
        static::multiLevelMenu();
    }

    /**
     * multiLevelDropdown
     *
     * @return  void
     *
     * @since  1.8.13
     */
    public static function multiLevelMenu(): void
    {
        if (!static::inited(__METHOD__)) {
            static::addCSS(static::phoenixName() . '/css/bootstrap/multi-level-menu.min.css');

            $js = <<<JS
$('.dropdown .dropdown-submenu > .dropdown-menu').each(function (i, e) {
    var menu = $(e);
    var navItem = menu.parents('.nav-item');

    if (navItem.find('> .dropdown-toggle').attr('data-toggle') === 'dropdown') {
        navItem.on('shown.bs.dropdown', function () {
            setTimeout(setOffset, 0);
        });
    } else {
        setOffset();
    }
    
    function setOffset() {
        if (menu.offset().left + menu.width() > $(window).width()) {
            menu.css('left', 'auto');
            menu.css('right', '100%');
        }
    }
});
$('.dropdown-toggle + .dropdown-menu').each(function (i, e) {
    var menu = $(e);

    if (menu.hasClass('dropdown-menu-right')) {
        return;
    }

    if (menu.parent().find('> .dropdown-toggle').attr('data-toggle') === 'dropdown') {
        menu.parent().on('shown.bs.dropdown', function () {
            setTimeout(setOffset, 0);
        });
    } else {
        setOffset();
    }

    function setOffset() {
        var pos = menu.parents('.nav-item').offset().left + menu.width();
        var winWidth = $(window).width();

        if (pos > winWidth) {
            menu.css('left', "-".concat(pos - winWidth + 15, "px"));
        }
    }
});
JS;

            \Phoenix\Script\PhoenixScript::domready($js);
        }
    }
}
