<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace Mobicms;

use Mobicms\System\Environment\UserAgentMiddleware;
use Psr\Container\ContainerInterface;
use Mezzio\Application;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper;
use Mezzio\Router\Middleware;
use Laminas\Stratigility\Middleware\ErrorHandler;

class Pipeline
{
    /**
     * Setup middleware pipeline
     *
     * @param ContainerInterface $container
     * @param string $serviceName
     * @param callable $callback
     * @param array|null $options
     * @return Application
     */
    public function __invoke(
        ContainerInterface $container,
        $serviceName,
        callable $callback,
        array $options = null
    ): Application {
        /** @var Application $app */
        $app = $callback();

        /** @var \Psr\Http\Server\MiddlewareInterface|\Psr\Http\Server\RequestHandlerInterface $val */
        foreach ($this->pipeList() as $val) {
            $app->pipe($val);
        }

        return $app;
    }

    /**
     * Get middleware chain
     *
     * @return array
     */
    private function pipeList(): array
    {
        return [
            // Register the error handler (should be the first)
            ErrorHandler::class,

            UserAgentMiddleware::class,
            Helper\ServerUrlMiddleware::class,

            // Register the routing middleware
            Middleware\RouteMiddleware::class,

            // Handle routing failures for common conditions
            Middleware\ImplicitHeadMiddleware::class,
            Middleware\ImplicitOptionsMiddleware::class,
            Middleware\MethodNotAllowedMiddleware::class,

            // Seed the UrlHelper with the routing results
            Helper\UrlHelperMiddleware::class,

            // Register the dispatch middleware
            Middleware\DispatchMiddleware::class,

            // If no Response is returned by any middleware, the NotFoundHandler kicks in
            NotFoundHandler::class,
        ];
    }
}
