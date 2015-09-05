<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Listener;

use Phoenix\Minify\JsMinify;
use Windwalker\Event\Event;

/**
 * The JsMinifyKistener class.
 *
 * @since  {DEPLOY_VERSION}
 */
class JsMinifyListener
{
	/**
	 * onPhoenixRenderScripts
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onPhoenixRenderScripts(Event $event)
	{
		$minify = new JsMinify($event['asset']);

		$minify->compress();
	}
}
