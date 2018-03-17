<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Windwalker\Core\Asset\AbstractScript;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Security\CsrfProtection;
use Windwalker\Ioc;
use Windwalker\Language\Language;
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
     * core
     *
     * @param string $formSelector
     * @param string $variable
     * @param array  $options
     *
     * @return void
     */
    public static function core($formSelector = '#admin-form', $variable = 'Phoenix', $options = [])
    {
        if (!static::inited(__METHOD__)) {
            JQueryScript::core();
            CoreScript::csrfToken();

            static::addJS(static::phoenixName() . '/js/phoenix/phoenix.min.js');
            static::addJS(static::phoenixName() . '/js/phoenix/form.min.js');

            static::data('phoenix.uri', Ioc::getUriData());
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $defaultOptions = [
                'theme' => BootstrapScript::$currentVersion === 3 ? 'bootstrap' : 'bootstrap4'
            ];

            $options = static::mergeOptions($defaultOptions, $options);
            $ui = '';

            if ($options['theme'] === 'bootstrap') {
                static::addJS(static::phoenixName() . '/js/phoenix/theme/bootstrap.js');
                $ui = 'PhoenixUIBootstrap3';
            } elseif ($options['theme'] === 'bootstrap4') {
                static::addJS(static::phoenixName() . '/js/phoenix/theme/bootstrap4.js');
                $ui = 'PhoenixUIBootstrap4';
            }

            $options = static::getJSObject($defaultOptions, $options);

            $js = <<<JS
// Phoenix Core
if (!$variable) {
  window.$variable = new PhoenixCore($options);
}
window.$variable.use([$ui, PhoenixForm, PhoenixLegacy]);
window.$variable.form('$formSelector');

$variable.Uri = jQuery.data(document, 'phoenix.uri');
JS;

            static::domReady($js);
        }
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
        static::router();

        static::data('phoenix.routes', [$route => (string) $url], true);
    }

    /**
     * router
     *
     * @return  void
     */
    public static function router()
    {
        if (!static::inited(__METHOD__)) {
            static::addJS(static::phoenixName() . '/js/phoenix/router.min.js');
        }
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
            static::core($selector);

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

            static::domReady($js);
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
                'placeholder_text_multiple' => Translator::translate('phoenix.chosen.text,multiple'),
                'placeholder_text_single' => Translator::translate('phoenix.chosen.text.single'),
                'no_results_text' => Translator::translate('phoenix.chosen.text.noresult'),
            ];

            $options = static::getJSObject(ArrayHelper::merge($defaultOptions, $options));

            $js = <<<JS
// Chosen select
(function($) {
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
})(jQuery);
JS;

            static::domReady($js);
        }
    }

    /**
     * translator
     *
     * @return  void
     */
    public static function translator()
    {
        if (!static::inited(__METHOD__)) {
            CoreScript::sprintf();

            $asset = static::getAsset();
            $asset->addScript(static::phoenixName() . '/js/phoenix/translator.min.js');
        }
    }

    /**
     * langKey
     *
     * @param   string $key
     *
     * @return  void
     * @throws \InvalidArgumentException
     */
    public static function translate($key)
    {
        static::translator();

        $text = Translator::translate($key);

        /** @var Language $language */
        $language = Translator::getInstance();
        $handler  = $language->getNormalizeHandler();

        $key = $handler($key);

        static::data('phoenix.languages', [$key => $text], true);
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

            static::domReady("$('$selector').multiselect('$selector', $options);");
        }
    }

    /**
     * formValidation
     *
     * @param string $selector
     * @param array  $options
     *
     * @return  void
     */
    public static function formValidation($selector = '#admin-form', $options = [])
    {
        if (!static::inited(__METHOD__)) {
            static::core();

            static::addJS(static::phoenixName() . '/js/string/punycode.min.js');
            static::addJS(static::phoenixName() . '/js/phoenix/validation.min.js');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            $defaultOptions = [
                'scroll' => [
                    'enabled' => true,
                    'offset' => -100,
                    'duration' => 1000,
                ],
            ];

            $options = static::getJSObject($defaultOptions, $options);

            static::translate('phoenix.message.validation.required');
            static::translate('phoenix.message.validation.failure');

            static::domReady("$('$selector').validation($options);");
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
    public static function keepAlive($url = './', $time = null)
    {
        if (!static::inited(__METHOD__)) {
            static::core();

            if ($time === null) {
                $config = Ioc::getConfig();
                $time   = $config->get('session.life_time', 3);

                $time *= 60000;
            }

            static::domReady("Phoenix.keepAlive('$url', $time);");
        }
    }

    /**
     * crypto
     *
     * @return  void
     */
    public static function crypto()
    {
        if (!static::inited(__METHOD__)) {
            $asset = static::getAsset();

            $asset->addScript(static::phoenixName() . '/js/phoenix/crypto.min.js');
        }
    }

    /**
     * ajax
     *
     * @param bool $token
     *
     * @return  void
     */
    public static function ajax($token = true)
    {
        if (!static::inited(__METHOD__)) {
            static::addJS(static::phoenixName() . '/js/phoenix/ajax.min.js');
        }

        if (!static::inited(__METHOD__, (bool) $token) && $token) {
            $token = CsrfProtection::getFormToken();
            static::internalJS("Phoenix.Ajax.headers._global['X-CSRF-Token'] = '{$token}'");
        }
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
            CoreScript::simpleUri();
            static::addJS(static::phoenixName() . '/js/phoenix/list-dependent.min.js');
        }

        if (!static::inited(__METHOD__, get_defined_vars())) {
            if (is_string($source)) {
                $options['ajax']['url'] = $source;
            } else {
                $options['source'] = $source;
            }

            $options = static::getJSObject($options);

            static::domReady("$('$selector').listDependent('$dependentSelector', $options);");
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
     * @since  __DEPLOY_VERSION__
     */
    public static function domReady($code, $name = null)
    {
        static $uid = 0;

        if ($name === null) {
            $name = (string) $uid++;
        }

        static::$domReady[$name] = $code;
    }
}
