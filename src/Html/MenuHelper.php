<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Phoenix\Html;

use Windwalker\Core\Package\PackageResolver;
use Windwalker\IO\Input;
use Windwalker\Utilities\ArrayHelper;

/**
 * The MenuHelper class.
 *
 * @since  __DEPLOY_VERSION__
 */
class MenuHelper
{
    /**
     * Property packageResolver.
     *
     * @var  PackageResolver
     */
    protected $packageResolver;

    /**
     * Property input.
     *
     * @var  Input
     */
    protected $input;

    /**
     * MenuHelper constructor.
     *
     * @param PackageResolver $packageResolver
     * @param Input           $input
     */
    public function __construct(PackageResolver $packageResolver, Input $input)
    {
        $this->packageResolver = $packageResolver;
        $this->input = $input;
    }

    /**
     * active
     *
     * @param string|array $path
     * @param array        $query
     * @param string       $menu
     *
     * @return bool
     */
    public function is($path, array $query = [], string $menu = 'mainmenu'): bool
    {
        if (is_array($path)) {
            foreach ($path as $p) {
                if ($this->is($p, $query, $menu)) {
                    return true;
                }
            }

            return false;
        }

        // Match route
        $route = $path;

        if (strpos($route, '@') === false) {
            $route = $this->packageResolver->getCurrentPackage()->getName() . '@' . $route;
        }

        $package = $this->packageResolver->getCurrentPackage();
        $matched = $package->router->getMatched();

        if ($matched->getName() === $route && $this->matchRequest($query)) {
            return true;
        }

        // If route not matched, we match extra values from routing.
        $routePaths = (array) $matched->getExtra('menu')[$menu];

        if ($routePaths === '') {
            return false;
        }

        foreach ($routePaths as $routePath) {
            $paths     = array_filter(explode('/', trim($path, '/')), 'strlen');
            $routePath = array_filter(explode('/', trim($routePath, '/')), 'strlen');

            foreach ($paths as $key => $pathSegment) {
                if (isset($routePath[$key]) && $routePath[$key] === $pathSegment && $this->matchRequest($query)) {
                    return true;
                }

                continue;
            }
        }

        return false;
    }

    /**
     * matchRequest
     *
     * @param array $query
     *
     * @return  boolean
     */
    protected function matchRequest(array $query = []): bool
    {
        if (!$query) {
            return true;
        }

        return !empty(ArrayHelper::query([$this->input->toArray()], $query));
    }
}
