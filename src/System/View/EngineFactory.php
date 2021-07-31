<?php

declare(strict_types=1);

namespace Mobicms\System\View;

use Mezzio\Helper\ServerUrlHelper;
use Mezzio\Helper\UrlHelper;
use Mobicms\Render\Engine;
use Psr\Container\ContainerInterface;

class EngineFactory
{
    public function __invoke(ContainerInterface $container): Engine
    {
        $engine = new Engine();

        $this->registerFunctions($container, $engine);
        $this->addTemplatePaths($container, $engine);

        return $engine;
    }

    private function registerFunctions(ContainerInterface $container, Engine $engine): void
    {
        /** @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);
        $engine->registerFunction('url', $urlHelper);

        /** @var ServerUrlHelper $serverUrlHelper */
        $serverUrlHelper = $container->get(ServerUrlHelper::class);
        $engine->registerFunction('serverurl', $serverUrlHelper);
    }

    private function addTemplatePaths(ContainerInterface $container, Engine $engine): void
    {
        /** @var array $config */
        $config = $container->get('config')['templates'] ?? [];

        /** @var array<string, string> $allPaths */
        $allPaths = $config['paths'] ?? [];

        foreach ($allPaths as $namespace => $path) {
            $engine->addPath($path, $namespace);
        }
    }
}
