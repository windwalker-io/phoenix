<?php

/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2020 .
 * @license    LGPL-2.0-or-later
 */

namespace Phoenix\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Windwalker\Core\Application\Middleware\AbstractWebMiddleware;
use Windwalker\Core\Router\MainRouter;
use Windwalker\Event\Event;
use Windwalker\Utilities\Classes\OptionAccessTrait;

/**
 * The KeepUrlQueryMiddleware class.
 *
 * @since  1.8.38
 */
class KeepUrlQueryMiddleware extends AbstractWebMiddleware
{
    use OptionAccessTrait;

    /**
     * KeepUrlQueryMiddleware constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @inheritDoc
     */
    public function __invoke(Request $request, Response $response, $next = null)
    {
        $key     = $this->getOption('key');
        $def     = $this->getOption('default');
        $filter  = $this->getOption('filter', 'cmd');
        $routeEnabled = $this->getOption('route_enabled');
        $afterHook    = $this->getOption('after_hook');
        $viewHook     = $this->getOption('view_hook');

        if (!$key) {
            throw new \LogicException('Please set key');
        }

        $value = $this->app->input->get($key, $def, $filter);

        // Auto add classroom id to routing
        $this->app->listen('onRouterBeforeRouteBuild', function (Event $event) use (
            $response,
            $request,
            $routeEnabled,
            $key,
            $value
        ) {
            $route = $event['route'];

            if (is_callable($routeEnabled) && !$routeEnabled($event, $this->options, $request, $response)) {
                return;
            }

            if (!$routeEnabled) {
                return;
            }

            /** @var MainRouter $router */
            $router = $event['router'];

            $hasMiddleware = $router::hasMiddleware($router->getRoute($route), static::class);

            if ($hasMiddleware) {
                $queries = $event['queries'];
                if ($value && empty($queries['type'])) {
                    $queries[$key]    = $value;
                    $event['queries'] = $queries;
                }
            }
        });

        if (is_callable($afterHook)) {
            $afterHook($value, $this->options, $request, $response);
        }

        if (is_callable($viewHook)) {
            $this->app->listen(
                'onViewBeforeRender',
                function (Event $event) use ($response, $request, $value, $viewHook) {
                    $viewHook($event['view'], $value, $this->options, $request, $response);
                }
            );
        }

        return $next($request, $response);
    }
}
