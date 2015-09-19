<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Listener;

use Phoenix\Minify\CssMinify;
use Windwalker\Event\Event;

/**
 * The CssMinifyListener class.
 *
 * @since  1.0
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
