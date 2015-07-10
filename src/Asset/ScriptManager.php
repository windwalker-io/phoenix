<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Asset;

use Phoenix\Uri\Uri;
use Windwalker\Ioc;

/**
 * The ScriptManager class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ScriptManager
{
	/**
	 * THe asset helpers storage.
	 *
	 * @var  AssetManager[]
	 */
	protected static $assetManagers = array();

	/**
	 * The module initialised.
	 *
	 * @var  boolean[]
	 */
	protected static $initialised = array();

	/**
	 * Modules handler storage.
	 *
	 * @var  callable[]
	 */
	protected static $modules = array();

	/**
	 * chosen
	 *
	 * @param string $selector
	 * @param int    $searchThreshold
	 *
	 * @return  void
	 */
	public static function chosen($selector = 'select', $searchThreshold = 10)
	{
		static $internals = [];

		$asset = static::getAssetManager();

		if (empty($internals[$selector . $searchThreshold]))
		{
			$js = <<<JS
jQuery(document).ready(function($)
{
	\$("{$selector}").chosen({disable_search_threshold: {$searchThreshold}});
});
JS;

			$asset->internalScript($js);

			$internals[$selector . $searchThreshold] = true;
		}

		// Init
		if (!empty(static::$initialised['chosen']))
		{
			return;
		}

		$asset->addScript(Uri::media(true) . 'phoenix/js/chosen/chosen.jquery.min.js');
		$asset->addStyle(Uri::media(true) . 'phoenix/js/chosen/chosen.min.css');
	}

	/**
	 * Load RequireJS.
	 *
	 * @return  void
	 */
	public static function requireJS()
	{
		if (!empty(static::$initialised['requirejs']))
		{
			return;
		}

		$asset = static::getAssetManager();

		$asset->addScript(Uri::media(true) . 'phoenix/js/core/require.js');

		static::$initialised['requirejs'] = true;
	}

	/**
	 * Load underscore.
	 *
	 * @param boolean $noConflict Enable underscore no conflict mode.
	 *
	 * @return  void
	 */
	public static function underscore($noConflict = true)
	{
		if (!empty(static::$initialised['underscore']))
		{
			return;
		}

		$asset = $asset = static::getAssetManager();

		$asset->addScript(Uri::media(true) . 'phoenix/js/core/underscore.js');

		if ($noConflict)
		{
			$asset->internalScript('var underscore = _.noConflict();');
		}

		static::$initialised['underscore'] = true;
	}

	/**
	 * Include Backbone. Note this library may not support old IE browser.
	 *
	 * Please see: http://backbonejs.org/
	 *
	 * @param   boolean $noConflict
	 *
	 * @return  void
	 */
	public static function backbone($noConflict = false)
	{
		if (!empty(static::$initialised['backbone']))
		{
			return;
		}

		// Dependency
		// \JHtmlJquery::framework(true);
		static::underscore();

		$asset = $asset = static::getAssetManager();

		$asset->addScript(Uri::media(true) . 'phoenix/js/core/backbone.js');

		if ($noConflict)
		{
			$asset->internalScript('var backbone = Backbone.noConflict();');
		}

		static::$initialised['backbone'] = true;
	}

	/**
	 * Set Module and callback.
	 *
	 * @param string   $name
	 * @param callable $handler
	 *
	 * @return  void
	 */
	public static function setModule($name, $handler)
	{
		$name = strtolower($name);

		static::$modules[$name] = $handler;
	}

	/**
	 * load
	 *
	 * @param string $name Module name.
	 *
	 * @return bool
	 */
	public static function load($name)
	{
		$name = strtolower($name);

		$args = func_get_args();

		if (empty(static::$modules[$name]))
		{
			$app = Ioc::getApplication();

			$app->addFlash(sprintf('Asset module: %s not found.', $name));

			return false;
		}

		if (! is_callable(static::$modules[$name]))
		{
			$app = Ioc::getApplication();

			$app->addFlash(sprintf('Asset module: %s is not callable.', $name));

			return false;
		}

		$name = array_shift($args);

		array_unshift($args, static::getAssetManager());
		array_unshift($args, $name);

		call_user_func_array(static::$modules[$name], $args);

		static::$initialised[$name] = true;

		return true;
	}

	/**
	 * Magic method to call modules.
	 *
	 * @param string $name
	 * @param array  $args
	 *
	 * @return  boolean
	 */
	public static function __callStatic($name, $args = array())
	{
		if (strpos($name, 'load') === 0)
		{
			$name = substr($name, 4);
		}
		else
		{
			return false;
		}

		array_unshift($args, $name);

		return call_user_func_array([get_called_class(), 'load'], $args);
	}

	/**
	 * Get AssetHelper by option name.
	 *
	 * @param   string $name Option name.
	 *
	 * @return  AssetManager
	 */
	public static function getAssetManager($name = null)
	{
		return Ioc::get('phoenix.asset', $name);
	}
}
