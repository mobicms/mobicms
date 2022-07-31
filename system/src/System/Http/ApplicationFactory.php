<?php

declare(strict_types=1);

namespace Mobicms\System\Http;

use Devanych\Di\FactoryInterface;
use HttpSoft\Basis\Application;
use HttpSoft\Basis\Handler\NotFoundJsonHandler;
use HttpSoft\Emitter\EmitterInterface;
use HttpSoft\Router\RouteCollector;
use HttpSoft\Runner\MiddlewarePipelineInterface;
use HttpSoft\Runner\MiddlewareResolverInterface;
use Psr\Container\{
    ContainerExceptionInterface,
    ContainerInterface,
    NotFoundExceptionInterface
};

final class ApplicationFactory implements FactoryInterface
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function create(ContainerInterface $container): Application
    {
        /**
         * @psalm-suppress MixedArgument
         * @psalm-suppress MixedArrayAccess
         */
        return new Application(
            $container->get(RouteCollector::class),
            $container->get(EmitterInterface::class),
            $container->get(MiddlewarePipelineInterface::class),
            $container->get(MiddlewareResolverInterface::class),
            new NotFoundJsonHandler($container->get('config')['debug'])
        );
    }
}
