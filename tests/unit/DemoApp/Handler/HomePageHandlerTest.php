<?php

declare(strict_types=1);

namespace MobicmsTest\DemoApp\Handler;

use Mobicms\DemoApp\Handler\HomePageHandler;
use Laminas\Diactoros\Response\HtmlResponse;
use Mobicms\Render\Engine;
use Mobicms\Testutils\DbHelpersTrait;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

class HomePageHandlerTest extends TestCase
{
    use DbHelpersTrait;

    public function setUp(): void
    {
        self::loadSqlDump('install/sql/demodata.sql');
    }

    public function testReturnsHtmlResponse(): void
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request
            ->expects($this->once())
            ->method('getServerParams')
            ->willReturn(['SERVER_SOFTWARE' => 'test']);
        $request
            ->expects($this->once())
            ->method('getAttributes')
            ->willReturn(
                [
                    'int'    => 123,
                    'string' => 'string',
                    'array'  => [],
                    'object' => new \stdClass(),
                    'other'  => null,
                ]
            );

        $renderer = $this->createMock(Engine::class);
        $renderer
            ->expects($this->once())
            ->method('render')
            ->willReturn('');

        $homePage = new HomePageHandler($renderer, self::$pdo);
        $response = $homePage->handle($request);

        $this->assertInstanceOf(HtmlResponse::class, $response);
    }
}
