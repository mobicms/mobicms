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

use Mezzio\Template\TemplateRendererInterface;

class Engine extends \Mobicms\Render\Engine implements TemplateRendererInterface
{
    public function getPaths(): array
    {
        return [];
    }

    /**
     * Add a path for template
     *
     * @param string $path
     * @param string|null $namespace
     */
    public function addPath(string $path, ?string $namespace = null): void
    {
        if (null !== $namespace) {
            $this->addFolder($namespace, $path);
        }
    }

    public function addDefaultParam(string $templateName, string $param, $value): void
    {
        $template = $templateName === self::TEMPLATE_ALL
            ? []
            : [$templateName];
        $this->addData([$param => $value], $template);
    }
}
