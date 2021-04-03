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
    public const USER_AGENT_ATTRIBUTE = 'http_user_agent';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->hasHeader('User-Agent')) {
            $userAgent = mb_substr($request->getHeaderLine('User-Agent'), 0, 255);
            $request = $request->withAttribute(
                self::USER_AGENT_ATTRIBUTE,
                htmlspecialchars($userAgent, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            );
        }

        return $handler->handle($request);
    }
}
