<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Windwalker\Core\Asset\AbstractScript;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\Language\Translator;
use Windwalker\Ioc;
use Windwalker\Language\Language;
use Windwalker\String\Str;
use Windwalker\Utilities\Arr;
use Windwalker\Utilities\ArrayHelper;

/**
 * The PhoenixScript class.
 *
 * @see    AbstractScript
 *
 * @since  1.0
 */
abstract class PhoenixScript extends AbstractPhoenixScript
{
    /**
     * Property store.
     *
     * @var  array
     */
    public static $data = [];

    /**
     * Property domReady.
     *
     * @var  array
     */
    public static $domReady = [];

    /**
     * phoenix
     *
     * @param string $variable
     * @param array  $options
     *
     * @return  void
     *
     * @since  3.3
     */
    public static function phoenix($variable = 'Phoenix', array $options = [])
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();
            CoreScript::csrfToken();

            static::addJS(static::phoenixName() . '/js/phoenix/phoenix.min.js');

            static::data('windwalker.debug', WINDWALKER_DEBUG);
            static::data('phoenix.date', [
                'timezone' => Ioc::getConfig()->get('system.timezone'),
                'empty' => Chronos::getNullDate()
            ]);
            static::data('phoenix.uri', array_merge(
                (array) Ioc::getUriData(),
                [
                    'asset' => [
                        'path' => static::getAsset()->path,
                        'root' => static::getAsset()->root,
                        'version' => static::getAsset()->getVersion()
                    ]
                ]
            ));
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $defaultOptions = [
                'theme' => BootstrapScript::$currentVersion === 3 ? 'bootstrap' : 'bootstrap4'
            ];

            $options = static::mergeOptions($defaultOptions, $options);

            $ui = '';

            if ($options['theme'] === 'bootstrap') {
                static::addJS(static::phoenixName() . '/js/phoenix/ui-bootstrap.min.js');
                $ui = 'PhoenixUIBootstrap3';
            } elseif ($options['theme'] === 'bootstrap4') {
                static::addJS(static::phoenixName() . '/js/phoenix/ui-bootstrap4.min.js');
                $ui = 'PhoenixUIBootstrap4';
            }

            $options = static::getJSObject($options);

            $js = <<<JS
// Phoenix Core
var $variable = new PhoenixCore($options);
$variable.use([$ui, PhoenixHelper, PhoenixRouter, PhoenixTranslator, PhoenixAjax, PhoenixCrypto, PhoenixStack]);
$variable.Uri = window.$variable.data('phoenix.uri');
JS;

            static::internalJS($js);
        }
    }

    /**
     * core
     *
     * @param string $formSelector
     * @param string $variable
     * @param array  $options
     *
     * @return void
     *
     * @deprecated Use PhoenixScript::form() instead.
     */
    public static function core($formSelector = '#admin-form', $variable = 'Phoenix', $options = [])
    {
        static::form($formSelector, $variable, $options);
    }

    /**
     * form
     *
     * @param string $formSelector
     * @param string $variable
     * @param array  $options
     *
     * @return  void
     *
     * @since  3.3
     */
    public static function form($formSelector = '#admin-form', $variable = 'Phoenix', $options = [])
    {
        if (!static::inited(__METHOD__, get_defined_vars())) {
            static::phoenix($variable, $options);
            static::addJS(static::phoenixName() . '/js/phoenix/form.min.js');

            $js = <<<JS
window.$variable.use([PhoenixForm, PhoenixLegacy]);
window.$variable.form('$formSelector');
JS;

            static::domready($js);
        }
    }

    /**
     * loadScript
     *
     * @param string $uri
     * @param bool   $autoConvert
     * @param string $variable
     *
     * @return  void
     *
     * @since  3.3
     */
    public static function loadScript($uri, $autoConvert = true, $variable = 'Phoenix')
    {
        $autoConvertArg = $autoConvert ? 'true' : 'false';

        static::domready("$variable.loadScript('$uri', $autoConvertArg)");
    }

    /**
     * route
     *
     * @param   string $route
     * @param   string $url
     *
     * @return  void
     * @throws \InvalidArgumentException
     */
    public static function addRoute($route, $url)
    {
        static::phoenix();

        static::data('phoenix.routes', [$route => (string) $url], true);
    }

    /**
     * router
     *
     * @deprecated No longer needs this method, just call PhoenixScript::phoenix();
     *
     * @return  void
     */
    public static function router()
    {
        //
    }

    /**
     * filterbar
     *
     * @param string $selector
     * @param string $variable
     * @param array  $options
     *
     * @return void
     */
    public static function grid($selector = '#admin-form', $variable = 'Phoenix', $options = [])
    {
        if (!static::inited(__METHOD__)) {
            static::core($selector, $variable);

            static::addJS(static::phoenixName() . '/js/phoenix/grid.js');

            static::translate('phoenix.message.delete.confirm');
            static::translate('phoenix.message.grid.checked');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            static::core($selector, $variable);

            $options = static::getJSObject($options);

            $js = <<<JS
$variable.use(PhoenixGrid);
$variable.grid('$selector', $options);
JS;

            static::domready($js);
        }
    }

    /**
     * chosen
     *
     * @param string $selector
     * @param array  $options
     *
     * @return  void
     */
    public static function chosen($selector = 'select', $options = [])
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();

            static::addJS(static::phoenixName() . '/js/chosen/chosen.min.js');

            if (BootstrapScript::$currentVersion === 3) {
                static::addCSS(static::phoenixName() . '/css/chosen/bootstrap-chosen.css');
            } else {
                static::addCSS(static::phoenixName() . '/css/chosen/bootstrap4-chosen.css');
            }
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $defaultOptions = [
                'allow_single_deselect' => true,
                'disable_search_threshold' => 10,
                'placeholder_text_multiple' => __('phoenix.chosen.text,multiple'),
                'placeholder_text_single' => __('phoenix.chosen.text.single'),
                'no_results_text' => __('phoenix.chosen.text.noresult'),
            ];

            $options = static::getJSObject(ArrayHelper::merge($defaultOptions, $options));

            $js = <<<JS
// Chosen for $selector
var select = $('{$selector}').chosen($options);

// Readonly hack by http://jsfiddle.net/eirc/v2es7L8o/
select.on('chosen:updated', function () {
    if (select.attr('readonly')) {
        var wasDisabled = select.is(':disabled');

        select.attr('disabled', 'disabled');
        select.data('chosen').search_field_disabled();

        if (wasDisabled) {
            select.attr('disabled', 'disabled');
        } else {
            select.removeAttr('disabled');
        }
    }
});

select.trigger('chosen:updated');
JS;

            static::domready($js);
        }
    }

    /**
     * select2
     *
     * @param string $selector
     * @param array  $options
     *
     * @return  void
     *
     * @since  1.6
     */
    public static function select2($selector = null, array $options = [])
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();

            static::addJS(static::phoenixName() . '/js/select2/select2.min.js');
            static::addCSS(static::phoenixName() . '/css/select2/select2.min.css');

            if (BootstrapScript::$currentVersion === 3) {
                static::addCSS(static::phoenixName() . '/css/select2/bs3/select2-bootstrap.min.css');
            } else {
                // TODO: Use official BS4 theme if PR merged. @see https://github.com/select2/select2-bootstrap-theme/pull/72
                static::addCSS(static::phoenixName() . '/css/select2/bs4/select2-bootstrap4.min.css');
            }

            static::translate('phoenix.select2.noresult');

            // Hack for readonly
            static::internalCSS(<<<CSS
select[readonly].select2-hidden-accessible + .select2-container {
  pointer-events: none;
  touch-action: none;
}
select[readonly].select2-hidden-accessible + .select2-container .select2-selection {
  background: #eee;
  box-shadow: none;
}
select[readonly].select2-hidden-accessible + .select2-container .select2-selection__arrow,
select[readonly].select2-hidden-accessible + .select2-container .select2-selection__clear {
  display: none;
}
CSS
            );
        }

        if ($selector !== null && !static::inited(__METHOD__, get_defined_vars())) {
            $defaultOptions = [
                'theme' => BootstrapScript::$currentVersion === 3 ? 'bootstrap' : 'bootstrap4',
                'allowClear' => true,
                'minimumResultsForSearch' => 10,
                'placeholder' => __('phoenix.select2.placeholder'),
                'language' => [
                    'noResults' => static::wrapFunction("return Phoenix.__('phoenix.select2.noresult')")
                    // TODO: More translations
                ]
            ];

            $optionsString = static::getJSObject($defaultOptions, $options);

            /*
             * Some workaround to fix select2 issues.
             * [1]: https://github.com/select2/select2/issues/4653#issuecomment-358944224
             * [2]: https://github.com/select2/select2/issues/4384#issuecomment-228464364
             */
            $js = <<<JS
// Select2 for: `$selector`
$('{$selector}').select2($optionsString);

// Fix for 4.0.3 [1]
$('{$selector}').on('select2:close', function () {
    var input = $(this).parent().find('.select2-search__field').focus();
    setTimeout(function () { input.focus(); }, 100);
});

// Fix for select2 v4.RC1 [2]
$('{$selector}').each(function () {
    var \$select2Container = $(this).data('select2').\$container;
    
    $(this).find('option[value=""]').each(function () {
        if ($(this).text().trim()) {
            \$select2Container.find('.select2-selection__placeholder').text($(this).text());
            return false;
        }
    });
});
JS;

            static::domready($js);
        }
    }

    /**
     * translator
     *
     * @deprecated No longer needs this method, just call PhoenixScript::phoenix();
     *
     * @return  void
     */
    public static function translator()
    {
        //
    }

    /**
     * langKey
     *
     * @param   string|array $key
     *
     * @return  void
     * @throws \InvalidArgumentException
     */
    public static function translate($key)
    {
        static::phoenix();

        if (is_array($key)) {
            foreach ($key as $keyName) {
                static::translate($keyName);
            }

            return;
        }

        $handler = Translator::getNormalizeHandler();
        $trans = [];

        if (Str::endsWith($key, '*')) {
            $key = substr($key, 0, -1);
            $key = $handler($key);

            $strings = Translator::getStrings();

            foreach ($strings as $k => $string) {
                $k = $handler($k);

                if (strpos($k, $key) === 0) {
                    $trans[$k] = __($k);
                }
            }
        } else {
            $key = $handler($key);

            $trans[$key] = __($key);
        }


        static::data('phoenix.languages', $trans, true);
    }

    /**
     * multiSelect
     *
     * @param string $selector
     * @param array  $options
     */
    public static function multiSelect($selector = '#admin-form table', $options = [])
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();

            static::addJS(static::phoenixName() . '/js/phoenix/multiselect.min.js');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $options = static::getJSObject($options);

            static::domready("$('$selector').multiselect('$selector', $options);");
        }
    }

    /**
     * formValidation
     *
     * @param string $selector
     * @param array  $options
     * @param string $variable
     *
     * @return  void
     *
     * @deprecated  Use validation() and v2.
     */
    public static function formValidation($selector = '#admin-form', $options = [], $variable = 'Phoenix')
    {
        if (!static::inited(__METHOD__)) {
            static::core($selector, $variable);

            static::addJS(static::phoenixName() . '/js/string/punycode.min.js');
            static::addJS(static::phoenixName() . '/js/phoenix/validation-v1.min.js');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $defaultOptions = [
                'scroll' => [
                    'enabled' => true,
                    'offset' => -100,
                    'duration' => 1000,
                    'duration' => 1000,
                ],
            ];

            $options = static::getJSObject($defaultOptions, $options);

            static::translate('phoenix.message.validation.required');
            static::translate('phoenix.message.validation.failure');

            static::domready("$variable.use(PhoenixValidationV1);");
            static::domready("$variable.validation('$selector', $options);");
        }
    }

    /**
     * validation
     *
     * @param string $selector
     * @param array  $options
     * @param string $variable
     *
     * @return  void
     *
     * @since  1.8.3
     */
    public static function validation(
        ?string $selector = '#admin-form',
        array $options = [],
        string $variable = 'Phoenix'
    ): void {
        if (!static::inited(__METHOD__)) {
            static::phoenix($variable);

            static::addJS(static::phoenixName() . '/js/string/punycode.min.js');
            static::addJS(static::phoenixName() . '/js/phoenix/validation.min.js');

            static::translate('phoenix.message.validation.*');
        }

        if (!static::inited(__METHOD__, $variable)) {
            static::domready("$variable.use(PhoenixValidation);");
        }

        if ($selector && !static::inited(__METHOD__, $selector)) {
            static::form($selector, $variable);

            $defaultOptions = [
                'scroll' => [
                    'enabled' => true,
                    'offset' => -100,
                    'duration' => 1000,
                ],
            ];

            $options = static::getJSObject($defaultOptions, $options);

            static::domready("$variable.validation('$selector', $options);");
        }
    }

    /**
     * keepAlive
     *
     * @param string        $url
     * @param integer|float $time
     *
     * @return  void
     */
    public static function keepAlive($url = null, $time = null)
    {
        if (!static::inited(__METHOD__)) {
            static::phoenix();

            if ($url === null) {
                $url = Ioc::getUriData()->root;
            }

            if ($time === null) {
                $config = Ioc::getConfig();
                $time   = $config->get('session.life_time', 3);

                $time *= 60000;
            }

            static::domready("Phoenix.keepAlive('$url', $time);");
        }
    }

    /**
     * crypto
     *
     * @deprecated No longer needs this method, just call PhoenixScript::phoenix();
     *
     * @return  void
     */
    public static function crypto()
    {
        //
    }

    /**
     * ajax
     *
     * @deprecated No longer needs this method, just call PhoenixScript::phoenix();
     *
     * @param bool $token
     *
     * @return  void
     */
    public static function ajax($token = true)
    {
        //
    }

    /**
     * listDependent
     *
     * @param string $selector
     * @param string $dependentSelector
     * @param mixed  $source
     * @param array  $options
     *
     * @return void
     */
    public static function listDependent($selector, $dependentSelector, $source, array $options = [])
    {
        if (!static::inited(__METHOD__)) {
            static::addJS(static::phoenixName() . '/js/phoenix/list-dependent.min.js');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            if (is_string($source)) {
                $options['ajax']['url'] = $source;
            } else {
                $options['source'] = $source;
            }

            $options = static::getJSObject($options);

            static::domready("$('$selector').listDependent('$dependentSelector', $options);");
        }
    }

    /**
     * store
     *
     * @param string $name
     * @param mixed  $store
     * @param bool   $merge
     *
     * @return  void
     *
     * @deprecated Use data() instead.
     */
    public static function store($name, $store, $merge = false)
    {
        if (!static::inited(__METHOD__)) {
            $js = <<<JS
// Init Phoenix Storage
window.Phoenix = window.Phoenix || {};
window.Phoenix.Store = window.Phoenix.Store || {
    get: function (name, def) {
        return this[name] !== undefined ? this[name] : def; 
    },
    set: function (name, value) {
        this[name] = value;
    },
    merge: function (name, value) {
        this[name] = $.extend(true, {}, this[name] || {}, value);
    }
};
JS;

            static::internalJS($js);
        }

        $store = static::getJSObject($store);

        if (!$merge) {
            $js = <<<JS
Phoenix.Store.set('$name', $store);
JS;
        } else {
            $js = <<<JS
Phoenix.Store.merge('$name', $store);
JS;
        }

        static::internalJS($js);
    }

    /**
     * data
     *
     * @param string $name
     * @param mixed  $store
     * @param bool   $merge
     *
     * @return  void
     * @throws \InvalidArgumentException
     */
    public static function data($name, $store, $merge = false)
    {
        if ($merge) {
            static::$data = Arr::mergeRecursive(static::$data, [$name => $store]);
        } else {
            static::$data[$name] = $store;
        }
    }

    /**
     * domReady
     *
     * @param string $code
     * @param string $name
     *
     * @return  void
     *
     * @since  3.3
     */
    public static function domready($code, $name = null)
    {
        static $uid = 0;

        if ($name === null) {
            $name = (string) $uid++;
        }

        static::$domReady[$name] = $code;
    }

    /**
     * shortLangCode
     *
     * @param string $code
     * @param string $separator
     *
     * @return  string
     *
     * @since  1.6.5
     */
    public static function shortLangCode($code, $separator = '_')
    {
        list($first, $last) = explode('-', $code, 2);

        if (strtolower($first) === strtolower($last)) {
            return strtolower($first);
        }

        return strtolower($first) . $separator . strtoupper($last);
    }

    /**
     * sortableJS
     *
     * @param string  $selector
     * @param array   $options
     *
     * @return  void
     *
     * @since  1.6.9
     */
    public static function sortableJS($selector = null, array $options = [])
    {
        if (!static::inited(__METHOD__)) {
            static::addJS(static::phoenixName() . '/js/sortablejs/Sortable.min.js');
        }

        if ($selector !== null && !static::inited(__METHOD__, get_defined_vars())) {
            $defaultOptions = [
                ''
            ];

            $optionsString = static::getJSObject($defaultOptions, $options);

            $js = <<<JS
$('$selector').each(function () {
  new Sortable(this, $optionsString);
});
JS;

            static::domready($js);
        }
    }

    /**
     * disableWhenSubmit
     *
     * @param string $formSelector
     * @param string $buttonsSelector
     * @param string $event
     *
     * @return  void
     *
     * @since  1.6.11
     */
    public static function disableWhenSubmit(
        $formSelector = '#admin-form',
        $buttonsSelector = '#admin-toolbar button, #admin-toolbar a',
        $event = 'phoenix.validate.success'
    ) {
        if (!static::inited(__METHOD__)) {
            static::domready(<<<JS
$('$formSelector').on('$event', () => {
  $('$buttonsSelector').attr('disabled', true).addClass('disabled').attr('href', 'javascript://');
});
JS
            );
        }
    }
}
