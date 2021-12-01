<?php

declare(strict_types=1);

namespace MobicmsTest\DemoApp\Handler;

use Mobicms\DemoApp\Handler\HomePageHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Mobicms\Render\Engine;
use Mobicms\Testutils\DbHelpersTrait;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Http\Message\ServerRequestInterface;

class HomePageHandlerTest extends MockeryTestCase
{
    use DbHelpersTrait;

    public function setUp(): void
    {
        self::loadSqlDump('.\install\sql\demodata.sql');
    }

    public function testReturnsHtmlResponse(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request
            ->shouldReceive('getServerParams')
            ->andReturn(['SERVER_SOFTWARE' => 'test']);
        $request
            ->shouldReceive('getAttribute')
            ->andReturn('');
        $request
            ->shouldReceive('getAttributes')
            ->andReturn(
                [
                    'int' => 123,
                    'string' => 'string',
                    'array' => [],
                    'object' => new \stdClass(),
                    'other' => null,
                ]
            );

        $renderer = Mockery::mock(Engine::class);
        $renderer
            ->shouldReceive('render')
            ->with('app::home-page', Mockery::andAnyOtherArgs())
            ->andReturn('');

        $homePage = new HomePageHandler($renderer, self::$pdo);
        $response = $homePage->handle($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}
