<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2018 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Listener;

use Phoenix\Script\PhoenixScript;
use Windwalker\Core\Asset\AssetManager;
use Windwalker\Event\Event;

/**
 * The JsStorageListener class.
 *
 * @since  __DEPLOY_VERSION__
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
        if (PhoenixScript::$data !== []) {
            /** @var AssetManager $asset */
            $asset = $event['asset'];

            $store = json_encode(PhoenixScript::$data, WINDWALKER_DEBUG ? JSON_PRETTY_PRINT : 0);

            $asset->internalJS("jQuery.data(document, $store);");

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
}
