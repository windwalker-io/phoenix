<?php
/**
 * Part of asukademy project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Uri;

use Windwalker\Core\Application\WebApplication;
use Windwalker\Ioc;

/**
 * The Uri class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class Uri extends \Windwalker\Uri\Uri
{
	const RELATIVE = true;
	const ABSOLUTE = false;

	/**
	 * Property app.
	 *
	 * @var WebApplication
	 */
	protected static $app;

	/**
	 * root
	 *
	 * @param bool $relative
	 *
	 * @return  string
	 */
	public static function root($relative = self::ABSOLUTE)
	{
		if ($relative == static::RELATIVE)
		{
			return static::getApplication()->get('uri.base.path');
		}
		else
		{
			return static::getApplication()->get('uri.base.full');
		}
	}

	/**
	 * host
	 *
	 * @return  string
	 */
	public static function host()
	{
		return static::getApplication()->get('uri.base.host');
	}

	/**
	 * current
	 *
	 * @param bool $relative
	 *
	 * @return  string
	 */
	public static function current($relative = self::ABSOLUTE)
	{
		if ($relative == static::RELATIVE)
		{
			return static::getApplication()->get('uri.route');
		}
		else
		{
			return static::getApplication()->get('uri.current');
		}
	}

	/**
	 * route
	 *
	 * @return  string
	 */
	public static function route()
	{
		return static::getApplication()->get('uri.route');
	}

	/**
	 * script
	 *
	 * @return  string
	 */
	public static function script()
	{
		return static::getApplication()->get('uri.script');
	}

	/**
	 * media
	 *
	 * @param bool $relative
	 *
	 * @return  string
	 */
	public static function media($relative = self::ABSOLUTE)
	{
		if ($relative == static::RELATIVE)
		{
			return static::getApplication()->get('uri.media.path');
		}
		else
		{
			return static::getApplication()->get('uri.media.full');
		}
	}

	/**
	 * addBase
	 *
	 * @param string $uri
	 * @param string $path
	 *
	 * @return  string
	 */
	public static function addBase($uri, $path = 'base.full')
	{
		if (strpos($uri, 'http') !== 0 && strpos($uri, '/') !== 0)
		{
			$uri = static::getApplication()->get('uri.' . $path) . $uri;
		}

		return $uri;
	}

	/**
	 * Method to get property Application
	 *
	 * @return  mixed
	 */
	public static function getApplication()
	{
		if (!static::$app)
		{
			static::$app = Ioc::getApplication();
		}

		return static::$app;
	}

	/**
	 * Method to set property application
	 *
	 * @param   mixed $app
	 *
	 * @return  void
	 */
	public static function setApplication($app)
	{
		static::$app = $app;
	}
}
