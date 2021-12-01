<?php

/**
 * This file is part of mobicms-modules/stub package.
 *
 * @see       https://github.com/mobicms-modules/stub for the canonical source repository
 * @license   https://github.com/mobicms-modules/stub/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms-modules/stub/blob/develop/README.md
 */

declare(strict_types=1);

namespace Mobicms\DemoApp\Handler;

use Mobicms\Render\Engine;
use Mobicms\System\Environment\IpAndUserAgentMiddleware;
use PDO;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class HomePageHandler implements RequestHandlerInterface
{
    private Engine $engine;
    private PDO $pdo;

    public function __construct(Engine $engine, PDO $pdo)
    {
        $this->engine = $engine;
        $this->pdo = $pdo;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Throwable
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $sever = $request->getServerParams();
        $data = [];
        $data['webServer'] = (string) $sever['SERVER_SOFTWARE'];
        $data['ip'] = (string) $request->getAttribute(IpAndUserAgentMiddleware::IP_ADDR, 'Empty');
        $data['ipViaProxy'] = (string) $request->getAttribute(IpAndUserAgentMiddleware::IP_VIA_PROXY_ADDR);
        $data['userAgent'] = (string) $request->getAttribute(IpAndUserAgentMiddleware::USER_AGENT, 'Empty');
        $data['pdoDemo'] = $this->pdoDemo();

        $data['psrattributes'] = [];

        /**
         * @var string $key
         * @var mixed $val
         */
        foreach ($request->getAttributes() as $key => $val) {
            if (is_string($val) || is_int($val)) {
                $data['psrattributes'][$key] = $val;
            } elseif (is_array($val)) {
                $data['psrattributes'][$key] = 'array';
            } elseif (is_object($val)) {
                $data['psrattributes'][$key] = 'object';
            } else {
                $data['psrattributes'][$key] = 'other';
            }
        }

        return new HtmlResponse($this->engine->render('app::home-page', $data));
    }

    private function pdoDemo(): array
    {
        $data = [];
        $req = $this->pdo->query('SELECT * FROM `test`');

        while ($res = $req->fetch(PDO::FETCH_ASSOC)) {
            $data[] = (string) $res['name'];
        }

        return $data;
    }
}
