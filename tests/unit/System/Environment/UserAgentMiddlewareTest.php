<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace MobicmsTest\System\Environment;

use Mobicms\System\Environment\UserAgentMiddleware;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class UserAgentMiddlewareTest extends MockeryTestCase
{
    private ServerRequestInterface $request;
    private RequestHandlerInterface $handler;

    public function testRequestHasAttributeWithUserAgent(): void
    {
        $this->prepare(true, 'test', 'test');
        $middleware = new UserAgentMiddleware();
        $result = $middleware->process($this->request, $this->handler);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    public function testTrimLongUserAgentTo255Symbols(): void
    {
        $this->prepare(true, str_repeat('a', 300), str_repeat('a', 255));
        $middleware = new UserAgentMiddleware();
        $middleware->process($this->request, $this->handler);
    }

    public function testSanitizeSpecialChars(): void
    {
        $this->prepare(true, '&"\'<>', '&amp;&quot;&#039;&lt;&gt;');
        $middleware = new UserAgentMiddleware();
        $middleware->process($this->request, $this->handler);
    }

    public function testWithoutUserAgentHeader(): void
    {
        $this->prepare(false, '', '');
        $middleware = new UserAgentMiddleware();
        $result = $middleware->process($this->request, $this->handler);
        $this->assertInstanceOf(ResponseInterface::class, $result);
    }

    private function prepare(bool $hasHeader, string $getHeaderLine, string $withAttribute): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('hasHeader')
            ->with('User-Agent')
            ->once()
            ->andReturn($hasHeader);

        if ($hasHeader) {
            $request->shouldReceive('getHeaderLine')
                ->with('User-Agent')
                ->once()
                ->andReturn($getHeaderLine);
            $request->shouldReceive('withAttribute')
                ->with(UserAgentMiddleware::USER_AGENT_ATTRIBUTE, $withAttribute)
                ->once()
                ->andReturn($request);
        } else {
            $request->shouldNotReceive('getHeaderLine');
            $request->shouldNotReceive('withAttribute');
        }

        $this->request = $request;
        $handler = Mockery::mock(RequestHandlerInterface::class);
        $handler->shouldReceive('handle')
            ->with($request)
            ->andReturn(Mockery::mock(ResponseInterface::class));
        $this->handler = $handler;
    }
}
