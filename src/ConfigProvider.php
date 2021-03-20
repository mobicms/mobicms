<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace Mobicms;

use Mezzio\Application;
use Mezzio\Template\TemplateRendererInterface;
use Mobicms\System\View\Renderer;
use Mobicms\System\View\RendererFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'debug'           => false,
            'zend-expressive' => $this->getExpressiveConfig(), //TODO: refactoring ???
            'dependencies'    => $this->getDependencies(),
            'templates'       => $this->getTemplates(),
        ];
    }

    private function getExpressiveConfig(): array
    {
        return [
            // Provide templates for the error handling middleware
            'error_handler' => [
                'template_404'   => 'error::404',
                'template_error' => 'error::error',
            ],
        ];
    }

    private function getDependencies(): array
    {
        return [
            'aliases' => [
                TemplateRendererInterface::class   => Renderer::class,
            ],

            'delegators' => [
                Application::class => [
                    Pipeline::class,
                ],
            ],

            'factories' => [
                Renderer::class                        => RendererFactory::class,
            ],
        ];
    }

    private function getTemplates(): array
    {
        return [
            'paths'     => [
                'error'  => __DIR__ . '/../templates/error',
                'layout' => __DIR__ . '/../templates/layout',
            ],
        ];
    }
}
