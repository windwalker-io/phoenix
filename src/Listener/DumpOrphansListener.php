<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Listener;

use Windwalker\Core\Application\WebApplication;
use Windwalker\Core\Ioc;
use Windwalker\Core\Language\Translator;
use Windwalker\Event\Event;

/**
 * The DumpOrphansListener class.
 *
 * @since  1.1
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
        $app = Ioc::getApplication();

        // Un comment this line, Translator will export all orphans to /cache/language
        if ($app->get('language.debug')) {
            Translator::dumpOrphans('ini');
        }
    }
}
