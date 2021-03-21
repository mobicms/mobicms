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
use Mezzio\Template\ArrayParametersTrait;
use Mezzio\Template\TemplateRendererInterface;

class Renderer implements TemplateRendererInterface
{
    use ArrayParametersTrait;

    /**
     * @var Engine
     */
    private $template;

    public function __construct(Engine $template)
    {
        $this->template = $template;
    }

    /**
     * {@inheritDoc}
     */
    public function render(string $name, $params = []): string
    {
        return $this->template->render($name, $this->normalizeParams($params));
    }

    /**
     * Add a path for template
     *
     * @param string $path
     * @param string|null $namespace
     */
    public function addPath(string $path, string $namespace = null): void
    {
        if (null !== $namespace) {
            $this->template->addFolder($namespace, $path);
        }
    }

    public function getPaths(): array
    {
        return [];
    }

    public function addDefaultParam(string $templateName, string $param, $value): void
    {
        $template = $templateName === self::TEMPLATE_ALL
            ? []
            : [$templateName];
        $this->template->addData([$param => $value], $template);
    }
}
