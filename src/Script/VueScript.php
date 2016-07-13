<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Script;

/**
 * The VueScript class.
 *
 * @since  {DEPLOY_VERSION}
 */
class VueScript extends AbstractPhoenixScript
{
	/**
	 * core
	 *
	 * @return  void
	 */
	public static function core()
	{
		if (!static::inited(__METHOD__))
		{
			static::addJS(static::phoenixName() . '/js/vue/vue.min.js');
		}
	}
}
