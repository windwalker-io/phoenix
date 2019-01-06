<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

use Windwalker\Core\Router\RouteCreator;

/** @var  $router  RouteCreator */

$router->group('package')
    ->register(function (RouteCreator $router) {
        $router->load(__DIR__ . '/Resources/routing/*.php');
    });
