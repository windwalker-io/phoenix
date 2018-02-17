<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Script;

use WhichBrowser\Parser;
use Windwalker\Core\Asset\AssetManager;
use Windwalker\Core\Browser\WhichBrowserFactory;
use Windwalker\Utilities\Arr;

/**
 * The PolyfillScript class.
 *
 * @since  1.4.7
 */
class PolyfillScript extends AbstractPhoenixScript
{
	/**
	 * addStyle
	 *
	 * @param string $url
	 * @param array  $options
	 * @param array  $attribs
	 *
	 * @return  AssetManager
	 */
	public static function addCSS($url, $options = [], $attribs = [])
	{
		$options = Arr::def($options, 'presets', ['stage-2']);

		if (isset($options['presets']))
		{
			$attribs = array_merge(static::scriptType($options['presets'], true), $attribs);
		}

		return parent::addCSS($url, $options, $attribs);
	}

	/**
	 * addScript
	 *
	 * @param string $url
	 * @param array  $options
	 * @param array  $attribs
	 *
	 * @return  AssetManager
	 */
	public static function addJS($url, $options = [], $attribs = [])
	{
		$options = Arr::def($options, 'presets', ['stage-2']);

		if (isset($options['presets']))
		{
			$attribs = array_merge(static::scriptType($options['presets'], true), $attribs);
		}

		return parent::addJS($url, $options, $attribs);
	}

	/**
	 * polyfill
	 *
	 * @param callable $condition
	 *
	 * @return  void
	 *
	 * @since  1.4.7
	 */
	public static function polyfill(callable $condition = null)
	{
		if (!static::inited(__METHOD__))
		{
			$condition = $condition ?: function (Parser $browser)
			{
				return $browser->isBrowser('Internet Explorer', '<=', 11);
			};

			if ($condition(WhichBrowserFactory::getInstance()))
			{
				parent::addJS(static::phoenixName() . '/js/polyfill/core.min.js');
			}
		}
	}

	/**
	 * babel
	 *
	 * @param array    $presets
	 * @param callable $condition
	 *
	 * @return  void
	 *
	 * @since  1.4.7
	 */
	public static function babel(array $presets = ['stage-2'], callable $condition = null)
	{
		if (!static::inited(__METHOD__))
		{
			$condition = $condition ?: function (Parser $browser)
			{
				return $browser->isBrowser('Internet Explorer', '<=', 11);
			};

			static::polyfill($condition);

			if (array_intersect($presets, ['stage-0', 'stage-1']) !== [] || $condition(WhichBrowserFactory::getInstance()))
			{
				parent::addJS(static::phoenixName() . '/js/polyfill/babel-polyfill.min.js');
				parent::addJS(static::phoenixName() . '/js/polyfill/babel.min.js');
			}
		}
	}

	/**
	 * scriptType
	 *
	 * @param array $presets
	 * @param bool  $asArray
	 *
	 * @return  string|array
	 */
	public static function scriptType(array $presets = ['stage-2'], $asArray = false)
	{
		$wBrowser = WhichBrowserFactory::getInstance();

		if ($asArray)
		{
			$return = [
				'type' => 'text/babel',
				'data-presets' => 'es2015,' . implode(',', $presets)
			];
		}
		else
		{
			$return = 'type="text/babel" data-presets="es2015,' . implode(',', $presets) . '"';
		}

		if ($wBrowser->isBrowser('Internet Explorer', '<=', 11))
		{
			return $return;
		}

		if (array_intersect($presets, ['stage-0', 'stage-1']) !== [])
		{
			return $return;
		}

		return $asArray ? [] : 'type="text/javascript"';
	}
}
