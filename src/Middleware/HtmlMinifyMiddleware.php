<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Phoenix\Middleware;

use Asika\Minifier\JsMinifier;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Windwalker\Core\Application\Middleware\AbstractWebMiddleware;
use Windwalker\Http\Response\HtmlResponse;
use Windwalker\Http\Stream\Stream;
use Windwalker\Middleware\MiddlewareInterface;

/**
 * The HtmlMinifyMiddleware class.
 *
 * @since  1.1
 */
class HtmlMinifyMiddleware extends AbstractWebMiddleware
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

        if (strpos($response->getHeaderLine('content-type'), 'text/html') !== false) {
            $body = $response->getBody()->__toString();
            
            // Minify JS first
            $body = preg_replace_callback(
                '/<script(.*?)>(.*?)<\/script>/is',
                function ($matches) {
                    if (!trim($matches[2])) {
                        return $matches[0];
                    }

                    return sprintf('<script%s>%s</script>', $matches[1], JsMinifier::process($matches[2]));
                },
                $body
            );

            // @link  http://stackoverflow.com/a/6225706
            $search = [
                '/\>[^\S ]+/s', // strip whitespaces after tags, except space
                '/[^\S ]+\</s', // strip whitespaces before tags, except space
                '/(\s)+/s',     // shorten multiple whitespace sequences
                '/<!--(.|\s)*?-->/' // Remove HTML comments
            ];

            $replace = [
                '>',
                '<',
                '\\1',
                ''
            ];

            $html = preg_replace($search, $replace, $body);

            $response = $response->withBody(new Stream('php://memory', Stream::MODE_READ_WRITE_FROM_BEGIN));
            $response->getBody()->write($html);
        }

        return $response;
    }
}
