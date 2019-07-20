<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$};

use Phoenix\Script\BootstrapScript;
use Phoenix\View\AbstractPhoenixHtmView;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Router\MainRouter;
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Event\Event;
use Windwalker\Router\Exception\RouteNotFoundException;

/**
 * The {$package.name.cap$}Package class.
 *
 * @since  1.0
 */
class {$package.name.cap$}Package extends AbstractPackage
{
    /**
     * initialise
     *
     * @return  void
     * @throws \ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     */
    public function boot()
    {
        parent::boot();

        // Add your own boot logic
    }

    /**
     * prepareExecute
     *
     * @return  void
     * @throws \Exception
     */
    protected function prepareExecute()
    {
        $this->checkAccess();

        // Assets
        BootstrapScript::css(4);
        BootstrapScript::script(4);
        BootstrapScript::fontAwesome(5, ['v4shims' => true]);
        Asset::addCSS($this->name . '/css/{$package.name.lower$}.css');

        // Language
        Translator::loadAll();
        Translator::loadAll($this, 'ini');

        // Use global lang prefix
        $this->getDispatcher()->listen(
            'onViewBeforeHandleData',
            static function (Event $event) {
                /** @var AbstractPhoenixHtmView $view */
                $view = $event['view'];

                if (!$view->getLangPrefix()) {
                    $view->setLangPrefix('{$project.name.lower$}.');
                }
            }
        );
    }

    /**
     * checkAccess
     *
     * @return  void
     *
     * @throws  RouteNotFoundException
     * @throws  \Exception
     */
    protected function checkAccess()
    {
        // Add your access checking
    }

    /**
     * postExecute
     *
     * @param string $result
     *
     * @return  string
     */
    protected function postExecute($result = null)
    {
        return $result;
    }
}
