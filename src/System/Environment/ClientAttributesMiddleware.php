<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace Mobicms\System\Environment;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ClientAttributesMiddleware implements MiddlewareInterface
{
    public const IP_ADDR = 'ip_address';
    public const IP_REMOTE_ADDR = 'ip_remote_address';
    public const IP_VIA_PROXY_ADDR = 'ip_via_proxy_address';
    public const USER_AGENT = 'user_agent';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $request = $this->determineIpRemoteAddress($request);
        $request = $this->determineUserAgent($request);

        return $handler->handle($request);
    }

    public function determineIpRemoteAddress(ServerRequestInterface $request): ServerRequestInterface
    {
        $server = $request->getServerParams();
        return isset($server['REMOTE_ADDR']) && $this->isValidIp((string) $server['REMOTE_ADDR'])
            ? $request->withAttribute(self::IP_REMOTE_ADDR, $server['REMOTE_ADDR'])
            : $request;
    }

    public function determineUserAgent(ServerRequestInterface $request): ServerRequestInterface
    {
        if ($request->hasHeader('User-Agent')) {
            $userAgent = mb_substr($request->getHeaderLine('User-Agent'), 0, 255);
            return $request->withAttribute(
                self::USER_AGENT,
                htmlspecialchars($userAgent, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            );
        }

        return $request;
    }

    public function isValidIp(string $ip): bool
    {
        return (bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6);
    }
}
