<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 __ORGANIZATION__.
 * @license    __LICENSE__
 */

use Windwalker\Core\Router\RouteCreator;

/** @var $router RouteCreator */

$router->group('{$controller.item.name.lower$}')
    // Set menu active name
    ->extra('menu', ['mainmenu' => '{$controller.list.name.lower$}'])
    ->register(function (RouteCreator $router) {
        // {$controller.item.name.cap$}
        $router->any('{$controller.item.name.lower$}', '/{$controller.item.name.lower$}/(id)')
            ->controller('{$controller.item.name.cap$}')
            ->extraValues([
                'layout' => '{$controller.item.name.lower$}'
            ]);

        // {$controller.list.name.cap$}
        $router->any('{$controller.list.name.lower$}', '/{$controller.list.name.lower$}')
            ->controller('{$controller.list.name.cap$}')
            ->extraValues([
                'layout' => '{$controller.list.name.lower$}'
            ]);
    });
