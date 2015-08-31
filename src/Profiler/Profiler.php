<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Profiler;

use Windwalker\Core\Facade\AbstractProxyFacade;
use Windwalker\Profiler\ProfilerInterface;

/**
 * The Profiler class.
 *
 * @see  \Windwalker\Profiler\Profiler
 *
 * @method  static  ProfilerInterface  mark($name, $data = array())
 *
 * @since  {DEPLOY_VERSION}
 */
class Profiler extends AbstractProxyFacade
{
	/**
	 * Property _key.
	 *
	 * @var  string
	 */
	protected static $_key = 'profiler';
}
