<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use Windwalker\Core\Router\RouteCreator;

/** @var RouteCreator $router */

$router->group('{$controller.item.name.lower$}')
    // Set menu active name
    ->extra('menu', ['mainmenu' => '{$controller.list.name.lower$}'])
    ->register(function (RouteCreator $router) {
        // {$controller.item.name.cap$}
        $router->any('{$controller.item.name.lower$}', '/{$controller.item.name.lower$}(/id)')
            ->controller('{$controller.item.name.cap$}')
            ->extra('layout', '{$controller.item.name.lower$}');

        // {$controller.list.name.cap$}
        $router->any('{$controller.list.name.lower$}', '/{$controller.list.name.lower$}')
            ->controller('{$controller.list.name.cap$}')
            ->postAction('CopyController')
            ->putAction('FilterController')
            ->patchAction('BatchController')
            ->extra('layout', '{$controller.list.name.lower$}');
    });
