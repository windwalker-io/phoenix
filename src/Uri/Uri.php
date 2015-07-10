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
	/**
	 * Property app.
	 *
	 * @var WebApplication
	 */
	protected static $app;

	/**
	 * root
	 *
	 * @param bool $path
	 *
	 * @return  string
	 */
	public static function root($path = false)
	{
		if ($path)
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
	 * @param bool $path
	 *
	 * @return  string
	 */
	public static function current($path = false)
	{
		if ($path)
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
	 * @param bool $path
	 *
	 * @return  string
	 */
	public static function media($path = false)
	{
		if ($path)
		{
			return static::getApplication()->get('uri.media.path');
		}
		else
		{
			return static::getApplication()->get('uri.media.full');
		}
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
