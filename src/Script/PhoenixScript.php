<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

use Windwalker\Core\Language\Translator;
use Windwalker\Language\Language;
use Windwalker\Registry\Format\JsonFormat;

/**
 * The PhoenixScript class.
 *
 * @see  \Phoenix\Script\ScriptManager
 * @see  \Phoenix\Script\Module\ModuleManager
 *
 * @since  {DEPLOY_VERSION}
 */
class PhoenixScript extends ScriptManager
{
	/**
	 * jquery
	 *
	 * @param   boolean $noConflict
	 *
	 * @return  void
	 */
	public static function jquery($noConflict = false)
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			$asset->addScript(static::phoenixName() . '/js/jquery/jquery.js');
		}

		if (!static::inited(__METHOD__, func_get_args()) && $noConflict)
		{
			$asset->internalScript('jQuery.noConflict();');
		}
	}

	/**
	 * core
	 *
	 * @param string $formSelector
	 * @param array  $options
	 *
	 * @return  void
	 */
	public static function core($formSelector = '#admin-form', $options = array())
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			static::jquery();

			$asset->addScript(static::phoenixName() . '/js/phoenix.js');
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$options = json_encode((object) $options);

			$js = <<<JS
// Phoenix Core
jQuery(document).ready(function($)
{
	var form = $('$formSelector');

	window.Phoenix = new PhoenixCore(form, $options);
});
JS;

			$asset->internalScript($js);
		}
	}

	/**
	 * filterbar
	 *
	 * @param string $formSelector
	 * @param array  $options
	 *
	 * @return  void
	 */
	public static function filterbar($formSelector = '#admin-form', $options = array())
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			static::core();

			$asset->addScript(static::phoenixName() . '/js/filterbar.js');
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$options = json_encode((object) $options);

			$js = <<<JS
// Filter bar
jQuery(document).ready(function($)
{
	var form = $('$formSelector');

	form.filterbar($options);
});
JS;

			$asset->internalScript($js);
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
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			static::jquery();

			$asset->addScript(static::phoenixName() . '/js/chosen/chosen.min.js');
			$asset->addStyle(static::phoenixName() . '/css/chosen/bootstrap-chosen.css');
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$defaultOptions = array(
				'allow_single_deselect'     => true,
				'disable_search_threshold'  => 10,
				'placeholder_text_multiple' => Translator::translate('phoenix.select.some.options'),
				'placeholder_text_single'   => Translator::translate('phoenix.select.an.option'),
				'no_results_text'           => Translator::translate('phoenix.select.no.result')
			);

			$options = json_encode((object) array_merge($defaultOptions, $options), JsonFormat::prettyPrint());

			$js = <<<JS
// Chosen select
jQuery(document).ready(function($)
{
	$('{$selector}').chosen($options);
});
JS;

			$asset->internalScript($js);
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
			$asset = static::getAsset();
			$asset->addScript(static::phoenixName() . '/js/string/translator.min.js');
		}
	}

	/**
	 * langKey
	 *
	 * @param   string  $key
	 *
	 * @return  void
	 */
	public function lang($key)
	{
		static::translator();

		$asset = static::getAsset();

		$text = Translator::translate($key);

		/** @var Language $language */
		$language = Translator::getInstance();
		$handler = $language->getNormalizeHandler();

		$key = call_user_func($handler, $key);

		$asset->internalScript("PhoenixTranslator.addKey('{$key}', '$text')");
	}

	public static function multiSelect($selector = '#admin-form table')
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			static::jquery();

			$asset->addScript(static::phoenixName() . '/js/grid/multiselect.min.js');
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$js = <<<JS
// Chosen select
jQuery(document).ready(function($)
{
	var multiSelect = new PhoenixMultiSelect('$selector');
});
JS;

			$asset->internalScript($js);
		}
	}

	public static function formValidation($selector = '#admin-form', $options = array())
	{
		$asset = static::getAsset();

		if (!static::inited(__METHOD__))
		{
			static::jquery();

			$asset->addScript(static::phoenixName() . '/js/string/punycode.min.js');
			$asset->addScript(static::phoenixName() . '/js/form/validation.min.js');
		}

		if (!static::inited(__METHOD__, func_get_args()))
		{
			$defaultOptions = array();

			$options = json_encode((object) array_merge($defaultOptions, $options));

			static::lang('phoenix.validation.fail');

			$js = <<<JS
// Chosen select
jQuery(document).ready(function($)
{
	$('$selector').validation($options);
});
JS;

			$asset->internalScript($js);
		}
	}
}
