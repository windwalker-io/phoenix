<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Windwalker\Core\Application\Middleware\AbstractWebMiddleware;
use Windwalker\Http\Response\HtmlResponse;
use Windwalker\Http\WebHttpServer;
use Windwalker\Middleware\MiddlewareInterface;

/**
 * The HtmlCompressMiddleware class.
 *
 * @since  {DEPLOY_VERSION}
 */
class HtmlCompressMiddleware extends AbstractWebMiddleware
{
	/**
	 * Middleware logic to be invoked.
	 *
	 * @param   Request                      $request  The request.
	 * @param   Response                     $response The response.
	 * @param   callable|MiddlewareInterface $next     The next middleware.
	 *
	 * @return  Response
	 */
	public function __invoke(Request $request, Response $response, $next = null)
	{
		/** @var Response $response */
		$response = $next($request, $response);

		if ($response instanceof HtmlResponse)
		{
			$response = $this->app->server->compressor->compress($response);
		}

		return $response;
	}
}
