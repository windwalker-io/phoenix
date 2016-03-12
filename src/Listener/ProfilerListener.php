<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Listener;

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
	 * onControllerBeforeExecute
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onControllerBeforeExecute(Event $event)
	{
		if (Ioc::exists('system.profiler'))
		{
			Ioc::get('system.profiler')->mark(__FUNCTION__);
		}
	}

	/**
	 * onControllerAfterExecute
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onControllerAfterExecute(Event $event)
	{
		if (Ioc::exists('system.profiler'))
		{
			Ioc::get('system.profiler')->mark(__FUNCTION__);
		}
	}

	/**
	 * onViewBeforeRender
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onViewBeforeRender(Event $event)
	{
		if (Ioc::exists('system.profiler'))
		{
			Ioc::get('system.profiler')->mark(__FUNCTION__);
		}
	}

	/**
	 * onViewAfterRender
	 *
	 * @param Event $event
	 *
	 * @return  void
	 */
	public function onViewAfterRender(Event $event)
	{
		if (Ioc::exists('system.profiler'))
		{
			Ioc::get('system.profiler')->mark(__FUNCTION__);
		}
	}
}
