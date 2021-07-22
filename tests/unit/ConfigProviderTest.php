<?php

declare(strict_types=1);

namespace MobicmsTest;

use Mezzio\Template\TemplateRendererInterface;
use Mobicms\ConfigProvider;
use Mobicms\Render\Engine;
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

    public function testProviderDefinesExpectedFactoryServices(): void
    {
        $factories = (array) $this->dependencies['factories'];
        $this->assertArrayHasKey(Engine::class, $factories);
        $this->assertArrayHasKey(TemplateRendererInterface::class, $factories);
    }
}
