<?php

declare(strict_types=1);

namespace MobicmsTest;

use Laminas\Stratigility\Middleware\ErrorHandler;
use Mezzio\Handler\NotFoundHandler;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\RouteMiddleware;
use Mobicms\Pipeline;
use Mobicms\System\Environment\IpAndUserAgentMiddleware;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Container\ContainerInterface;
use Mezzio\Application;

class PipelineTest extends MockeryTestCase
{
    public function testPipelineReturnsAnApplicationObject(): void
    {
        $application = function (): Application {
            $mock = Mockery::mock(Application::class);
            $mock->shouldReceive('pipe')->with(ErrorHandler::class)->once();
            $mock->shouldReceive('pipe')->with(IpAndUserAgentMiddleware::class)->once();
            $mock->shouldReceive('pipe')->with(ServerUrlMiddleware::class)->once();
            $mock->shouldReceive('pipe')->with(RouteMiddleware::class)->once();
            $mock->shouldReceive('pipe')->with(ImplicitHeadMiddleware::class)->once();
            $mock->shouldReceive('pipe')->with(ImplicitOptionsMiddleware::class)->once();
            $mock->shouldReceive('pipe')->with(MethodNotAllowedMiddleware::class)->once();
            $mock->shouldReceive('pipe')->with(UrlHelperMiddleware::class)->once();
            $mock->shouldReceive('pipe')->with(DispatchMiddleware::class)->once();
            $mock->shouldReceive('pipe')->with(NotFoundHandler::class)->once();
            return $mock;
        };

        /** @var ContainerInterface $container */
        $container = Mockery::mock(ContainerInterface::class);
        $delegator = (new Pipeline())($container, '', $application);
        $this->assertInstanceOf(Application::class, $delegator);
    }
}
