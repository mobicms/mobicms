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

use Mobicms\System\Environment\ClientAttributesMiddleware;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class ClientAttributesMiddlewareTest extends MockeryTestCase
{
    public function testDetermineIp(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('getServerParams')
            ->once()
            ->andReturn(['REMOTE_ADDR' => '192.168.0.9']);
        $request->shouldReceive('withAttribute')
            ->with(ClientAttributesMiddleware::IP_REMOTE_ADDR, '192.168.0.9')
            ->once()
            ->andReturn($request);

        $middleware = new ClientAttributesMiddleware();
        $middleware->determineIpRemoteAddress($request);
    }

    public function testInvalidIp(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('getServerParams')
            ->once()
            ->andReturn(['REMOTE_ADDR' => '392.268.0.9']);
        $request->shouldNotReceive('withAttribute');

        $middleware = new ClientAttributesMiddleware();
        $middleware->determineIpRemoteAddress($request);
    }

    public function testDetermineUserAgent(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('hasHeader')
            ->with('User-Agent')
            ->once()
            ->andReturn(true);
        $request->shouldReceive('getHeaderLine')
            ->with('User-Agent')
            ->once()
            ->andReturn('Test User Agent');
        $request->shouldReceive('withAttribute')
            ->with(ClientAttributesMiddleware::USER_AGENT, 'Test User Agent')
            ->once()
            ->andReturn($request);

        $middleware = new ClientAttributesMiddleware();
        $middleware->determineUserAgent($request);
    }

    public function testTrimLongUserAgentTo255Symbols(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('hasHeader')
            ->with('User-Agent')
            ->once()
            ->andReturn(true);
        $request->shouldReceive('getHeaderLine')
            ->with('User-Agent')
            ->once()
            ->andReturn(str_repeat('a', 300));
        $request->shouldReceive('withAttribute')
            ->with(ClientAttributesMiddleware::USER_AGENT, str_repeat('a', 255))
            ->once()
            ->andReturn($request);

        $middleware = new ClientAttributesMiddleware();
        $middleware->determineUserAgent($request);
    }

    public function testSanitizeSpecialChars(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('hasHeader')
            ->with('User-Agent')
            ->once()
            ->andReturn(true);
        $request->shouldReceive('getHeaderLine')
            ->with('User-Agent')
            ->once()
            ->andReturn('&"\'<>');
        $request->shouldReceive('withAttribute')
            ->with(ClientAttributesMiddleware::USER_AGENT, '&amp;&quot;&#039;&lt;&gt;')
            ->once()
            ->andReturn($request);

        $middleware = new ClientAttributesMiddleware();
        $middleware->determineUserAgent($request);
    }

    public function testWithoutUserAgentHeader(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('hasHeader')
            ->with('User-Agent')
            ->once()
            ->andReturn(false);
        $request->shouldNotReceive('getHeaderLine');
        $request->shouldNotReceive('withAttribute');

        $middleware = new ClientAttributesMiddleware();
        $middleware->determineUserAgent($request);
    }

    public function testProcess(): void
    {
        $request = Mockery::mock(ServerRequestInterface::class);
        $request->shouldReceive('getServerParams')
            ->once()
            ->andReturn(['REMOTE_ADDR' => '192.168.0.9']);
        $request->shouldReceive('withAttribute')
            ->with(ClientAttributesMiddleware::IP_REMOTE_ADDR, '192.168.0.9')
            ->once()
            ->andReturn($request);
        $request->shouldReceive('hasHeader')
            ->with('User-Agent')
            ->once()
            ->andReturn(true);
        $request->shouldReceive('getHeaderLine')
            ->with('User-Agent')
            ->once()
            ->andReturn('Test User Agent');
        $request->shouldReceive('withAttribute')
            ->with(ClientAttributesMiddleware::USER_AGENT, 'Test User Agent')
            ->once()
            ->andReturn($request);

        $handler = Mockery::mock(RequestHandlerInterface::class);
        $handler->shouldReceive('handle')
            ->with($request)
            ->andReturn(Mockery::mock(ResponseInterface::class));

        $middleware = new ClientAttributesMiddleware();
        $middleware->process($request, $handler);
    }
}
