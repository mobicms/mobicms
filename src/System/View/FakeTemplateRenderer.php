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

use Mezzio\Template\ArrayParametersTrait;
use Mezzio\Template\TemplateRendererInterface;
use Mobicms\Render\Engine;
use Psr\Container\ContainerInterface;

/**
 * @psalm-suppress MissingConstructor
 */
class FakeTemplateRenderer implements TemplateRendererInterface
{
    use ArrayParametersTrait;

    private Engine $engine;

    public function __invoke(ContainerInterface $container): TemplateRendererInterface
    {
        $this->engine = $container->get(Engine::class);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function render(string $name, $params = []): string
    {
        return $this->engine->render($name, $this->normalizeParams($params));
    }

    // @codeCoverageIgnoreStart
    public function addPath(string $path, ?string $namespace = null): void
    {
    }

    public function getPaths(): array
    {
        return [];
    }

    public function addDefaultParam(?string $templateName, string $param, $value): void
    {
    }
    // @codeCoverageIgnoreEnd
}
