<?php

declare(strict_types=1);

namespace MobicmsTest\DemoApp\Handler;

use Mobicms\DemoApp\Handler\HomePageHandler;
use Mobicms\DemoApp\Handler\HomePageHandlerFactory;
use Mobicms\Render\Engine;
use PDO;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class HomePageHandlerFactoryTest extends TestCase
{
    public function testFactoryWithTemplate(): void
    {
        $container = $this->createMock(ContainerInterface::class);
        $container
            ->method('get')
            ->withConsecutive(
                [PDO::class],
                [Engine::class]
            )
            ->willReturn(
                $this->createMock(PDO::class),
                $this->createMock(Engine::class)
            );
        $factory = new HomePageHandlerFactory();

        $this->assertInstanceOf(HomePageHandler::class, $factory($container));
    }
}
