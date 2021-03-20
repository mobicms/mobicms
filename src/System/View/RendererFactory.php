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
        $config = $container->get('config');
        $config = $config['templates'] ?? [];

        // Create the engine instance:
        $engine = new Engine();
        $engine->registerFunction('url', $container->get(UrlHelper::class));
        $engine->registerFunction('serverurl', $container->get(ServerUrlHelper::class));
        $this->addTemplatePath($engine, $config);

        // Inject engine
        return new Renderer($engine);
    }

    private function addTemplatePath(Engine $engine, array $config): void
    {
        $allPaths = isset($config['paths']) && is_array($config['paths']) ? $config['paths'] : [];

        foreach ($allPaths as $namespace => $path) {
            $engine->addFolder($namespace, $path);
        }
    }
}
