<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Phoenix\Uri;

use Windwalker\Core\Application\WebApplication;
use Windwalker\Ioc;
use Windwalker\Registry\Registry;

/**
 * The Uri class.
 * 
 * @since  1.0
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
			return static::getUriData()->get('base.path');
		}
		else
		{
			return static::getUriData()->get('base.full');
		}
	}

	/**
	 * host
	 *
	 * @return  string
	 */
	public static function host()
	{
		return static::getUriData()->get('base.host');
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
			return static::getUriData()->get('route');
		}
		else
		{
			return static::getUriData()->get('current');
		}
	}

	/**
	 * full
	 *
	 * @return  string
	 */
	public static function full()
	{
		return static::getUriData()->get('full');
	}

	/**
	 * route
	 *
	 * @return  string
	 */
	public static function route()
	{
		return static::getUriData()->get('route');
	}

	/**
	 * script
	 *
	 * @return  string
	 */
	public static function script()
	{
		return static::getUriData()->get('script');
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
			return static::getUriData()->get('media.path');
		}
		else
		{
			return static::getUriData()->get('media.full');
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
			$uri = static::getUriData()->get($path) . $uri;
		}

		return $uri;
	}

	/**
	 * getUri
	 *
	 * @return  Registry
	 */
	public static function getUriData()
	{
		return static::getApplication()->getContainer()->get('uri');
	}

	/**
	 * Method to get property Application
	 *
	 * @return  WebApplication
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
