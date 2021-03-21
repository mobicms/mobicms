<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace MobicmsTest;

use Mezzio\Template\TemplateRendererInterface;
use Mobicms\ConfigProvider;
use Mobicms\System\View\Engine;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    private array $config = [];
    private array $dependencies = [];

    public function setUp(): void
    {
        $this->config = (new ConfigProvider())();
        $this->dependencies = (array) $this->config['dependencies'];
    }

    public function testConfigHasDebugKeyWithBooleanFalseValue(): void
    {
        $this->assertArrayHasKey('debug', $this->config);
        $this->assertFalse($this->config['debug']);
    }

    public function testProviderDefinesExpectedAliases(): void
    {
        $aliases = (array) $this->dependencies['aliases'];
        $this->assertArrayHasKey(TemplateRendererInterface::class, $aliases);
    }

    public function testProviderDefinesExpectedFactoryServices(): void
    {
        $factories = (array) $this->dependencies['factories'];
        $this->assertArrayHasKey(Engine::class, $factories);
    }
}
