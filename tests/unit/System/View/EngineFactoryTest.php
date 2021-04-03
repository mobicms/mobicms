<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace MobicmsTest\System\View;

use Mezzio\Helper\ServerUrlHelper;
use Mezzio\Helper\UrlHelper;
use Mobicms\Render\Engine;
use Mobicms\System\View\EngineFactory;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Container\ContainerInterface;

class EngineFactoryTest extends MockeryTestCase
{
    private ContainerInterface $container;

    public function setUp(): void
    {
        $container = Mockery::mock(ContainerInterface::class);
        $serverUrlHelper = Mockery::mock(ServerUrlHelper::class);
        $urlHelper = Mockery::mock(UrlHelper::class);

        $container->shouldReceive('get')->with(ServerUrlHelper::class)->andReturn($serverUrlHelper);
        $container->shouldReceive('get')->with(UrlHelper::class)->andReturn($urlHelper);
        $container
            ->shouldReceive('get')
            ->with('config')
            ->andReturn(['templates' => ['paths' => ['test' => __DIR__,],],]);
        $this->container = $container;
    }

    public function testFactoryReturnsInstanceOfEngine(): Engine
    {
        $engine = (new EngineFactory())($this->container);
        $this->assertInstanceOf(Engine::class, $engine);
        return $engine;
    }

    /**
     * @depends testFactoryReturnsInstanceOfEngine
     */
    public function testUrlExtensionIsRegisteredByDefault(Engine $engine): void
    {
        $this->assertTrue($engine->doesFunctionExist('url'));
        $this->assertTrue($engine->doesFunctionExist('serverurl'));
    }

    /**
     * @depends testFactoryReturnsInstanceOfEngine
     */
    public function testEngineHasConfiguredFolder(Engine $engine): void
    {
        $result = $engine->getFolder('test');
        $this->assertEquals(__DIR__, $result[0]);
    }
}
