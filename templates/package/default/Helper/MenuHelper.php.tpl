<?php
/**
 * Part of {$package.name.cap$} project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace {$package.namespace$}{$package.name.cap$}\Helper;

use Windwalker\Core\Language\Translator;
use Windwalker\Core\View\Helper\AbstractHelper;
use Windwalker\Dom\HtmlElement;
use Windwalker\Filesystem\Filesystem;
use Windwalker\Ioc;
use Windwalker\String\StringInflector;
use Windwalker\Utilities\ArrayHelper;

/**
 * The MenuHelper class.
 *
 * @since  1.0
 */
class MenuHelper extends AbstractHelper
{
    const PLURAL = 'plural';
    const SINGULAR = 'singular';

    /**
     * getSubmenus
     *
     * @return  array
     */
    public function getSubmenus()
    {
        $menus   = $this->findViewMenus(static::PLURAL);
        $view    = $this->getParent()->getView();
        $package = $view->getPackage();
        $links   = [];

        foreach ($menus as $menu) {
            $active = static::active($menu, 'submenu');

            $links[] = new HtmlElement(
                'a',
                __($package->getName() . '.' . $menu),
                [
                    'href' => $view->getRouter()->route($menu),
                    'class' => $active
                ]
            );
        }

        return $links;
    }

    /**
     * guessSubmenus
     *
     * @param string $inflection
     *
     * @return array
     */
    protected function findViewMenus($inflection = self::PLURAL)
    {
        $inflector = StringInflector::getInstance();

        $viewFolder = PACKAGE_{$package.name.upper$}_ROOT . '/View';

        $views = Filesystem::folders($viewFolder);
        $menus = [];

        /** @var \SplFileInfo $view */
        foreach ($views as $view) {
            if ($view->isFile()) {
                continue;
            }

            $name = strtolower($view->getBasename());

            if ($inflection == static::PLURAL && $inflector->isPlural($name)) {
                $menus[] = $name;
            } elseif ($inflection == static::SINGULAR && $inflector->isSingular($name)) {
                $menus[] = $name;
            }
        }

        return $menus;
    }

    /**
     * active
     *
     * @param string $path
     * @param array  $query
     * @param string $menu
     *
     * @return string
     */
    public function active($path, $query = [], $menu = 'mainmenu')
    {
        $view = $this->getParent()->getView();

        // Match route
        $route = $path;

        if (strpos($route, '@') === false) {
            $route = $view->getPackage()->getName() . '@' . $route;
        }

        if ($view['app']->get('route.matched') == $route && $this->matchRequest($query)) {
            return 'active';
        }

        // If route not matched, we match extra values from routing.
        $routePath = $view['app']->get('route.extra.menu.' . $menu);

        $path      = array_filter(explode('/', trim($path, '/')), 'strlen');
        $routePath = array_filter(explode('/', trim($routePath, '/')), 'strlen');

        $success = false;

        foreach ($path as $key => $pathSegment) {
            if (isset($routePath[$key]) && $routePath[$key] == $pathSegment && $this->matchRequest($query)) {
                $success = true;
            } else {
                $success = false;
            }
        }

        return $success ? 'active' : '';
    }

    /**
     * matchRequest
     *
     * @param array $query
     *
     * @return  boolean
     */
    protected function matchRequest($query = [])
    {
        $input = Ioc::getInput();

        if (!$query) {
            return true;
        }

        return !empty(ArrayHelper::query([$input->toArray()], $query));
    }
}
