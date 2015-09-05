<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Listener;

use Phoenix\Minify\CssMinify;
use Windwalker\Event\Event;

/**
 * The CssMinifyListener class.
 *
 * @since  {DEPLOY_VERSION}
 */
class CssMinifyListener
{
	/**
	 * onPhoenixRenderScripts
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onPhoenixRenderStyles(Event $event)
	{
		$minify = new CssMinify($event['asset']);

		$minify->compress();
	}
}
