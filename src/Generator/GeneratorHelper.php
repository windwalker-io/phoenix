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
	 * @param string $prefix
	 * @param string $modifier
	 *
	 * @return string
	 */
	public static function addBeforePlaceholder($name, $origin, $replace, $prefix = '//', $modifier = null)
	{
		$replace = $replace . '$1';

		return static::replacePlaceholder($name, $origin, $replace, $prefix, $modifier);
	}

	/**
	 * addAfterPlaceholder
	 *
	 * @param string $name
	 * @param string $origin
	 * @param string $replace
	 * @param string $prefix
	 * @param string $modifier
	 *
	 * @return string
	 */
	public static function addAfterPlaceholder($name, $origin, $replace, $prefix = '//', $modifier = null)
	{
		$replace = '$1' . $replace;

		return static::replacePlaceholder($name, $origin, $replace, $prefix, $modifier);
	}

	/**
	 * replacePlaceholder
	 *
	 * @param string $name
	 * @param string $origin
	 * @param string $replace
	 * @param string $prefix
	 * @param string $modifier
	 *
	 * @return string
	 */
	public static function replacePlaceholder($name, $origin, $replace, $prefix = '//', $modifier = null)
	{
		return preg_replace(static::getPlaceholderRegex($name, $prefix, $modifier), $replace, $origin);
	}

	/**
	 * getPlaceholderRegex
	 *
	 * @param string $name
	 * @param string $prefix
	 * @param string $modifier
	 *
	 * @return string
	 */
	public static function getPlaceholderRegex($name, $prefix = '//', $modifier = null)
	{
		return '/([ \t]*' . preg_quote($prefix, '/') . '\s+@muse-placeholder\s+' . $name . '\s+[ a-zA-Z\d._-]+)/' . $modifier;
	}
}
