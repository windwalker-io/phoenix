<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Listener;

use Phoenix\Profiler\Profiler;
use Windwalker\Event\Event;
use Windwalker\Ioc;

/**
 * The ProfilerListener class.
 *
 * @since  {DEPLOY_VERSION}
 */
class ProfilerListener
{
	/**
	 * onControllerAfterExecute
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onControllerBeforeExecute(Event $event)
	{
		/** @var Profiler $profiler */
		$profiler = Ioc::get('system.profiler');

		$profiler->mark(__FUNCTION__);
	}

	public function onControllerAfterExecute(Event $event)
	{
		/** @var Profiler $profiler */
		$profiler = Ioc::get('system.profiler');

		$profiler->mark(__FUNCTION__);
	}

	public function onViewBeforeRender(Event $event)
	{
		/** @var Profiler $profiler */
		$profiler = Ioc::get('system.profiler');

		$profiler->mark(__FUNCTION__);
	}

	public function onViewAfterRender(Event $event)
	{
		/** @var Profiler $profiler */
		$profiler = Ioc::get('system.profiler');

		$profiler->mark(__FUNCTION__);
	}
}
