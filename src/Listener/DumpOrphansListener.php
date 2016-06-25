<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Listener;

use Phoenix\Language\TranslatorHelper;
use Windwalker\Core\Application\WebApplication;
use Windwalker\Event\Event;

/**
 * The DumpOrphansListener class.
 *
 * @since  {DEPLOY_VERSION}
 */
class DumpOrphansListener
{
	/**
	 * onAfterExecute
	 *
	 * @param Event $event
	 *
	 * @return  void
	 * 
	 * @throws \Windwalker\Filesystem\Exception\FilesystemException
	 */
	public function onAfterExecute(Event $event)
	{
		/** @var WebApplication $app */
		$app= $event['app'];

		// Un comment this line, Translator will export all orphans to /cache/language
		if ($app->get('language.debug'))
		{exit(' @Checkpoint');
			TranslatorHelper::dumpOrphans('ini');
		}
	}
}
