<?php

declare(strict_types=1);

namespace MobicmsTest\DemoApp;

use Mobicms\DemoApp\ConfigProvider;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    private array $config = [];

    public function setUp(): void
    {
        $this->config = (new ConfigProvider())();
    }

    public function testConfigHasDependencies(): void
    {
        $this->assertArrayHasKey('dependencies', $this->config);

        $dependencies = $this->config['dependencies'] ?? [];
        $this->assertNotEmpty($dependencies);
        $this->assertIsArray($dependencies);
    }

    public function testConfigHasTemplates(): void
    {
        $this->assertArrayHasKey('templates', $this->config);

        $templates = $this->config['templates'] ?? [];
        $this->assertNotEmpty($templates);
        $this->assertIsArray($templates);
    }
}
