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
use Mobicms\System\View\Renderer;
use PHPUnit\Framework\TestCase;

class ConfigProviderTest extends TestCase
{
    public function testInvocationReturnsArray(): array
    {
        $config = (new ConfigProvider())();
        $this->assertIsArray($config);

        return $config;
    }

    /**
     * @depends testInvocationReturnsArray
     */
    public function testConfigHasDebugKeyWithBooleanFalseValue(array $config): void
    {
        $this->assertArrayHasKey('debug', $config);
        $this->assertFalse($config['debug']);
    }

    /**
     * @depends testInvocationReturnsArray
     */
    public function testProviderDefinesExpectedAliases(array $config) : void
    {
        $aliases = $config['dependencies']['aliases'];
        $this->assertArrayHasKey(TemplateRendererInterface::class, $aliases);
    }

    /**
     * @depends testInvocationReturnsArray
     */
    public function testProviderDefinesExpectedFactoryServices(array $config): void
    {
        $factories = $config['dependencies']['factories'];

        $this->assertArrayHasKey(Renderer::class, $factories);
    }
}
