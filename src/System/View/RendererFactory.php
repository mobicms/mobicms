<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace Mobicms\System\View;

use Mobicms\Render\Engine;
use Psr\Container\ContainerInterface;
use Mezzio\Helper\ServerUrlHelper;
use Mezzio\Helper\UrlHelper;

use function is_array;

class RendererFactory
{
    public function __invoke(ContainerInterface $container): Renderer
    {
        $engine = new Engine();

        /** @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);
        $engine->registerFunction('url', $urlHelper);

        /** @var ServerUrlHelper $serverUrlHelper */
        $serverUrlHelper = $container->get(ServerUrlHelper::class);
        $engine->registerFunction('serverurl', $serverUrlHelper);

        /** @var array<array-key, mixed> $config */
        $config = $container->get('config')['templates'] ?? [];
        $this->addTemplatePath($engine, $config);

        // Inject engine
        return new Renderer($engine);
    }

    private function addTemplatePath(Engine $engine, array $config): void
    {
        /** @var array<string, string> $allPaths */
        $allPaths = isset($config['paths']) && is_array($config['paths']) ? $config['paths'] : [];

        foreach ($allPaths as $namespace => $path) {
            $engine->addFolder($namespace, $path);
        }
    }
}
