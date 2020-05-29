<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 LYRASOFT.
 * @license    LGPL-2.0-or-later
 */

namespace Phoenix\Listener;

use Phoenix\Script\PhoenixScript;
use Windwalker\Core\Asset\AssetManager;
use Windwalker\Event\Event;

/**
 * The JsStorageListener class.
 *
 * @since  3.3
 */
class JsCommandListener
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
        /** @var AssetManager $asset */
        $asset = $event['asset'];

        if (PhoenixScript::$data !== []) {
            $store = json_encode(PhoenixScript::$data, WINDWALKER_DEBUG ? JSON_PRETTY_PRINT : 0);

            PhoenixScript::addInitialise("jQuery.data(document, $store);");
        }

        $scripts = &$asset->getScripts();

        foreach ($scripts as $url => &$script) {
            if ($url === 'https://phoenix_body') {
                $script['options']['body'] = implode("\n", PhoenixScript::$initialise);
            }
        }

        if (is_array(PhoenixScript::$domReady) && count(PhoenixScript::$domReady) > 0) {
            $js = implode("\n", PhoenixScript::$domReady);

            if (WINDWALKER_DEBUG) {
                $js = str_replace("\n", "\n  ", $js);

                $js = "/* DOM READY START */\n\n  $js\n\n/* DOM READY END */";
            }

            $js = <<<JS
jQuery(function ($) {
$js
});
JS;

            $asset->internalJS($js);
        }
    }
}
