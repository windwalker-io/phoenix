<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Generator;

/**
 * The GeneratorHelper class.
 *
 * @since  {DEPLOY_VERSION}
 */
class GeneratorHelper
{
	/**
	 * addBeforePlaceholder
	 *
	 * @param string $name
	 * @param string $origin
	 * @param string $replace
	 * @param string $modifier
	 *
	 * @return  string
	 */
	public static function addBeforePlaceholder($name, $origin, $replace, $modifier = null)
	{
		$replace = $replace . '$1';

		return static::replacePlaceholder($name, $origin, $replace, $modifier);
	}

	/**
	 * addAfterPlaceholder
	 *
	 * @param string $name
	 * @param string $origin
	 * @param string $replace
	 * @param string $modifier
	 *
	 * @return  string
	 */
	public static function addAfterPlaceholder($name, $origin, $replace, $modifier = null)
	{
		$replace = '$1' . $replace;

		return static::replacePlaceholder($name, $origin, $replace, $modifier);
	}

	/**
	 * replacePlaceholder
	 *
	 * @param string $name
	 * @param string $origin
	 * @param string $replace
	 * @param string $modifier
	 *
	 * @return  string
	 */
	public static function replacePlaceholder($name, $origin, $replace, $modifier = null)
	{
		return preg_replace(static::getPlaceholderRegex($name, $modifier), $replace, $origin);
	}

	/**
	 * getPlaceholderRegex
	 *
	 * @param string $name
	 * @param string $modifier
	 *
	 * @return  string
	 */
	public static function getPlaceholderRegex($name, $modifier = null)
	{
		return '/(\/\/\s@muse-placeholder\s+' . $name . ')/' . $modifier;
	}
}
