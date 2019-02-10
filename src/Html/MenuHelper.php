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
use Windwalker\Router\Route;
use Windwalker\Utilities\Arr;
use Windwalker\Utilities\ArrayHelper;

/**
 * The MenuHelper class.
 *
 * @since  1.8
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
     * Property activeString.
     *
     * @var  string
     */
    protected $activeString = 'active';

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
                if (isset($routePath[$key]) && $routePath[$key] === $pathSegment && $this->matchRequest($query)
                    && count($paths) === ($key + 1)) {
                    return true;
                }

                continue;
            }
        }

        return false;
    }

    /**
     * inGroup
     *
     * @param string|array $groups
     * @param array        $query
     *
     * @return  bool
     *
     * @since  1.8
     */
    public function inGroup($groups, array $query = []): bool
    {
        $groups = Arr::toArray($groups);

        $matched = $this->getMatchedRoute();
        
        $currentGroups = array_keys($matched->getExtra('groups', []));
        
        $active = array_intersect($groups, $currentGroups) !== [];
        
        return $active && $this->matchRequest($query);
    }

    /**
     * inPackage
     *
     * @param string|array $packages
     * @param array        $query
     *
     * @return  bool
     *
     * @since  1.8
     */
    public function inPackage($packages, array $query = []): bool
    {
        $packages = Arr::toArray($packages);

        $route = $this->getMatchedRoute();

        return in_array((string) $route->getExtra('package'), $packages, true)
            && $this->matchRequest($query);
    }

    /**
     * active
     *
     * @param string|array $path
     * @param array        $query
     * @param string       $menu
     *
     * @return  string
     *
     * @since  1.8
     */
    public function active($path, array $query = [], string $menu = 'mainmenu'): string
    {
        return $this->is($path, $query, $menu) ? $this->activeString : '';
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

    /**
     * getMatchedRoute
     *
     * @return  Route
     *
     * @since  1.8
     */
    public function getMatchedRoute(): Route
    {
        $package = $this->packageResolver->getCurrentPackage();
        return $package->router->getMatched();
    }

    /**
     * Method to get property ActiveString
     *
     * @return  string
     *
     * @since  1.8
     */
    public function getActiveString(): string
    {
        return $this->activeString;
    }

    /**
     * Method to set property activeString
     *
     * @param   string $activeString
     *
     * @return  static  Return self to support chaining.
     *
     * @since  1.8
     */
    public function activeString(string $activeString)
    {
        $this->activeString = $activeString;

        return $this;
    }
}
