<?php

declare(strict_types=1);

namespace MobicmsTest\DemoApp\Handler;

use Mobicms\DemoApp\Handler\HomePageHandler;
use Mobicms\DemoApp\Handler\HomePageHandlerFactory;
use Mobicms\Render\Engine;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PDO;
use Psr\Container\ContainerInterface;

class HomePageHandlerFactoryTest extends MockeryTestCase
{
    public function testFactoryWithTemplate(): void
    {
        $container = Mockery::mock(ContainerInterface::class);
        $container
            ->shouldReceive('get')
            ->with(PDO::class)
            ->andReturn(Mockery::mock(PDO::class));
        $container
            ->shouldReceive('get')
            ->with(Engine::class)
            ->andReturn(Mockery::mock(Engine::class));
        $factory = new HomePageHandlerFactory();

        $this->assertInstanceOf(HomePageHandler::class, $factory($container));
    }
}
