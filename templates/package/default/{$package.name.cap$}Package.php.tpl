<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$};

use Phoenix\Script\BootstrapScript;
use Windwalker\Core\Asset\Asset;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Core\Router\MainRouter;
use Windwalker\Debugger\Helper\DebuggerHelper;
use Windwalker\Filesystem\Folder;
use Windwalker\Router\Exception\RouteNotFoundException;

/**
 * The {$package.name.cap$}Package class.
 *
 * @since  1.0
 */
class {$package.name.cap$}Package extends AbstractPackage
{
    const DIR = __DIR__;

    const FILE = __FILE__;

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
        BootstrapScript::fontAwesome(5);
        Asset::addCSS($this->name . '/css/{$package.name.lower$}.css');

        // Language
        Translator::loadAll($this, 'ini');
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
        if (WINDWALKER_DEBUG) {
            if (class_exists(DebuggerHelper::class)) {
                DebuggerHelper::addCustomData(
                    'Language Orphans',
                    '<pre>' . Translator::getFormattedOrphans() . '</pre>'
                );
            }
        }

        return $result;
    }

    /**
     * loadRouting
     *
     * @param MainRouter $router
     * @param string     $group
     *
     * @return MainRouter
     */
    public function loadRouting(MainRouter $router, $group = null)
    {
        $router = parent::loadRouting($router, $group);

        $router->group($group, function (MainRouter $router) {
            $router->addRouteFromFiles(Folder::files(__DIR__ . '/Resources/routing'), $this->getName());
            // Merge other routes here...
        });

        return $router;
    }
}
