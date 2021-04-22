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

use Mezzio\Template\TemplateRendererInterface;
use Mobicms\Render\Engine;
use Mobicms\System\View\FakeTemplateRenderer;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Container\ContainerInterface;

class FakeTemplateRendererTest extends MockeryTestCase
{
    public function testInvoke(): void
    {
        $engine = Mockery::mock(Engine::class);
        $container = Mockery::mock(ContainerInterface::class);
        $container->shouldReceive('get')
            ->with(Engine::class)
            ->andReturn($engine);
        $renderer = (new FakeTemplateRenderer())($container);
        $this->assertInstanceOf(TemplateRendererInterface::class, $renderer);
    }

    public function testRender(): void
    {
        $engine = Mockery::mock(Engine::class);
        $engine->shouldReceive('render')
            ->once()
            ->with('test::test', []);
        $container = Mockery::mock(ContainerInterface::class);
        $container->shouldReceive('get')
            ->with(Engine::class)
            ->andReturn($engine);
        $renderer = (new FakeTemplateRenderer())($container);
        $renderer->render('test::test');
    }
}