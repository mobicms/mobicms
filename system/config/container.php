<?php

declare(strict_types=1);

use HttpSoft\Basis\Application;
use HttpSoft\Basis\Response\CustomResponseFactory;
use HttpSoft\Cookie\CookieManager;
use HttpSoft\Cookie\CookieManagerInterface;
use HttpSoft\Emitter\EmitterInterface;
use HttpSoft\Emitter\SapiEmitter;
use HttpSoft\ErrorHandler\ErrorHandlerMiddleware;
use HttpSoft\Router\RouteCollector;
use HttpSoft\Runner\MiddlewarePipeline;
use HttpSoft\Runner\MiddlewarePipelineInterface;
use HttpSoft\Runner\MiddlewareResolver;
use HttpSoft\Runner\MiddlewareResolverInterface;
use Mobicms\Container\Container;
use Mobicms\Render\Engine;
use Mobicms\System\App\ApplicationFactory;
use Mobicms\System\Config\ConfigContainer;
use Mobicms\System\Db\PdoFactory;
use Mobicms\System\ErrorHandler\ErrorHandlerMiddlewareFactory;
use Mobicms\System\Config\ConfigInterface;
use Mobicms\System\Log\LoggerFactory;
use Mobicms\System\Session\SessionMiddleware;
use Mobicms\System\Session\SessionMiddlewareFactory;
use Mobicms\System\View\EngineFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Log\LoggerInterface;

$container = new Container(
    [
        'services'  =>
            [
                ConfigInterface::class => new ConfigContainer(require_once __DIR__ . '/config.php'),
            ],
        'factories' =>
            [
                Application::class                 => ApplicationFactory::class,
                CookieManagerInterface::class      => fn() => new CookieManager(),
                EmitterInterface::class            => fn() => new SapiEmitter(),
                Engine::class                      => EngineFactory::class,
                ErrorHandlerMiddleware::class      => ErrorHandlerMiddlewareFactory::class,
                LoggerInterface::class             => LoggerFactory::class,
                MiddlewarePipelineInterface::class => fn() => new MiddlewarePipeline(),
                MiddlewareResolverInterface::class => fn(ContainerInterface $c) => new MiddlewareResolver($c),
                PDO::class                         => PdoFactory::class,
                ResponseFactoryInterface::class    => fn() => new CustomResponseFactory(),
                RouteCollector::class              => fn() => new RouteCollector(),
                SessionMiddleware::class           => SessionMiddlewareFactory::class,
            ],
    ]
);

return $container;
