<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
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
 * @since  1.0
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
