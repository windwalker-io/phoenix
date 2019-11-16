<?php
/**
 * Part of Phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Listener;

use Phoenix\Minify\JsMinify;
use Windwalker\Event\Event;

/**
 * The JsMinifyKistener class.
 *
 * @since  1.0
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
    public function onAssetRenderScripts(Event $event)
    {
        if (!class_exists(JsMinify::class)) {
            throw new \DomainException('Please install asika/minify ~1.0 first');
        }

        $minify = new JsMinify($event['asset']);

        $minify->compress();
    }
}
