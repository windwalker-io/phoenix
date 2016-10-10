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
use Windwalker\Utilities\ArrayHelper;

/**
 * The PhoenixScript class.
 *
 * @see  AbstractScript
 *
 * @since  1.0
 */
abstract class PhoenixScript extends AbstractPhoenixScript
{
	/**
	 * core
	 *
	 * @param string $formSelector
	 * @param string $variable
	 * @param array  $options
	 *
	 * @return void
	 */
	public static function core($formSelector = '#admin-form', $variable = 'Phoenix', $options = array())
	{
		if (!static::inited(__METHOD__))
		{
			JQueryScript::core();
			CoreScript::csrfToken();

			static::addJS(static::phoenixName() . '/js/phoenix/phoenix.js');
		}

		if (!static::inited(__METHOD__, get_defined_vars()))
		{
			$defaultOptions = array(
				'theme' => 'bootstrap',
				'uri' => get_object_vars(Ioc::getUriData())
			);

			$options = static::mergeOptions($defaultOptions, $options);

			if ($options['theme'] == 'bootstrap')
			{
				static::addJS(static::phoenixName() . '/js/phoenix/theme/bootstrap.js');
			}

			$options = static::getJSObject($defaultOptions, $options);

			$js = <<<JS
// Phoenix Core
jQuery(document).ready(function($)
{
	var core = $('$formSelector').phoenix($options);

	window.$variable = window.$variable || {};
	window.$variable = $.extend(window.$variable, core);
});
JS;

			static::internalJS($js);
		}
	}

	/**
	 * route
	 *
	 * @param   string  $route
	 * @param   string  $url
	 *
	 * @return  void
	 */
	public static function addRoute($route, $url)
	{
		static::router();

		static::internalJS("Phoenix.Router.add('$route', '$url')");
	}

	/**
	 * router
	 *
	 * @return  void
	 */
	public static function router()
	{
		if (!static::inited(__METHOD__))
		{
			static::addJS(static::phoenixName() . '/js/phoenix/router.min.js');

			$uri = static::getJSObject((array) Ioc::get('uri'));

			static::internalJS(<<<JS
Phoenix.Uri = $uri;
JS
);
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
	public static function grid($selector = '#admin-form', $variable = 'Phoenix' ,$options = array())
	{
		if (!static::inited(__METHOD__))
		{
			static::core($selector);

			static::addJS(static::phoenixName() . '/js/phoenix/grid.js');

			static::translate('phoenix.message.delete.confirm');
			static::translate('phoenix.message.grid.checked');
		}

		if (!static::inited(__METHOD__, get_defined_vars()))
		{
			$options = static::getJSObject($options);

			$js = <<<JS
// Gird and filter bar
jQuery(document).ready(function($)
{
	var form = $('$selector');
	var grid = form.grid(form.phoenix(), $options);

	window.$variable = window.$variable || {};
	window.$variable.Grid = window.$variable.Grid || grid;
});
JS;

			static::internalJS($js);
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
	public static function chosen($selector = 'select', $options = array())
	{
		if (!static::inited(__METHOD__))
		{
			JQueryScript::core();

			static::addJS(static::phoenixName() . '/js/chosen/chosen.min.js');
			static::addCSS(static::phoenixName() . '/css/chosen/bootstrap-chosen.css');
		}

		if (!static::inited(__METHOD__, get_defined_vars()))
		{
			$defaultOptions = array(
				'allow_single_deselect'     => true,
				'disable_search_threshold'  => 10,
				'placeholder_text_multiple' => Translator::translate('phoenix.chosen.text,multiple'),
				'placeholder_text_single'   => Translator::translate('phoenix.chosen.text.single'),
				'no_results_text'           => Translator::translate('phoenix.chosen.text.noresult')
			);

			$options = static::getJSObject(ArrayHelper::merge($defaultOptions, $options));

			$js = <<<JS
// Chosen select
jQuery(document).ready(function($)
{
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
});
JS;

			static::internalJS($js);
		}
	}

	/**
	 * translator
	 *
	 * @return  void
	 */
	public static function translator()
	{
		if (!static::inited(__METHOD__))
		{
			CoreScript::underscoreString(true);

			$asset = static::getAsset();
			$asset->addScript(static::phoenixName() . '/js/phoenix/translator.min.js');
		}
	}

	/**
	 * langKey
	 *
	 * @param   string  $key
	 *
	 * @return  void
	 */
	public static function translate($key)
	{
		static::translator();

		$asset = static::getAsset();

		$text = Translator::translate($key);

		/** @var Language $language */
		$language = Translator::getInstance();
		$handler = $language->getNormalizeHandler();

		$key = call_user_func($handler, $key);

		$asset->internalScript("Phoenix.Translator.addKey('{$key}', '$text')");
	}

	/**
	 * multiSelect
	 *
	 * @param string $selector
	 * @param array  $options
	 */
	public static function multiSelect($selector = '#admin-form table', $options = array())
	{
		if (!static::inited(__METHOD__))
		{
			JQueryScript::core();

			static::addJS(static::phoenixName() . '/js/phoenix/multiselect.min.js');
		}

		if (!static::inited(__METHOD__, get_defined_vars()))
		{
			$options = static::getJSObject($options);

			$js = <<<JS
// Chosen select
jQuery(document).ready(function($)
{
	$('$selector').multiselect('$selector', $options);
});
JS;

			static::internalJS($js);
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
	public static function formValidation($selector = '#admin-form', $options = array())
	{
		if (!static::inited(__METHOD__))
		{
			static::core();

			static::addJS(static::phoenixName() . '/js/string/punycode.min.js');
			static::addJS(static::phoenixName() . '/js/phoenix/validation.min.js');
		}

		if (!static::inited(__METHOD__, get_defined_vars()))
		{
			$defaultOptions = array(
				'scroll' => array(
					'enabled'  => true,
					'offset'   => -100,
					'duration' => 1000
				)
			);

			$options = static::getJSObject(ArrayHelper::merge($defaultOptions, $options));

			static::translate('phoenix.message.validation.required');
			static::translate('phoenix.message.validation.failure');

			$js = <<<JS
// Chosen select
jQuery(document).ready(function($)
{
	$('$selector').validation($options);
});
JS;

			static::internalJS($js);
		}
	}

	/**
	 * keepAlive
	 *
	 * @param string  $url
	 * @param integer $time
	 *
	 * @return  void
	 */
	public static function keepAlive($url = './', $time = null)
	{
		if (!static::inited(__METHOD__))
		{
			static::core();

			if ($time === null)
			{
				$config = Ioc::getConfig();
				$time = $config->get('session.life_time', 3);

				$time = $time * 60000;
			}

			$js = <<<JS
jQuery(document).ready(function($) {
    Phoenix.keepAlive('$url', $time);
});
JS;

			static::getAsset()->internalScript($js);
		}
	}

	/**
	 * crypto
	 *
	 * @return  void
	 */
	public static function crypto()
	{
		if (!static::inited(__METHOD__))
		{
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
		if (!static::inited(__METHOD__))
		{
			static::addJS(static::phoenixName() . '/js/phoenix/ajax.min.js');
		}

		if (!static::inited(__METHOD__) && $token)
		{
			$token = CsrfProtection::getFormToken();
			static::internalJS("Phoenix.Ajax.headers._global['X-Csrf-Token'] = '{$token}'");
		}
	}
}
