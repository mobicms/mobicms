<?php

declare(strict_types=1);

namespace MobicmsTest;

use Laminas\ConfigAggregator\PhpFileProvider;
use Mobicms\ConfigProvider;
use Mobicms\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerFactoryTest extends TestCase
{
    public function testFactoryReturnsContainerObject(): ContainerInterface
    {
        $container = ContainerFactory::getContainer();
        $this->assertInstanceOf(ContainerInterface::class, $container);

        return $container;
    }

    /**
     * @depends testFactoryReturnsContainerObject
     */
    public function testContainerHasConfigurationArray(ContainerInterface $container): void
    {
        $this->assertIsArray($container->get('config'));
    }

    public function testConfigHasNecessaryProviders(): void
    {
        $providers = ContainerFactory::getConfigProviders();
        $this->assertEquals(\Mezzio\ConfigProvider::class, $providers[1]);
        $this->assertEquals(\Mezzio\Helper\ConfigProvider::class, $providers[2]);
        $this->assertEquals(\Mezzio\Router\FastRouteRouter\ConfigProvider::class, $providers[3]);
        $this->assertEquals(\Mezzio\Router\ConfigProvider::class, $providers[4]);
        $this->assertEquals(\Laminas\HttpHandlerRunner\ConfigProvider::class, $providers[5]);
        $this->assertEquals(ConfigProvider::class, $providers[6]);
        $this->assertEquals(new PhpFileProvider((string) M_PATH_CONFIG . 'packages/*.php'), $providers[7]);
        $this->assertEquals(
            new PhpFileProvider((string) M_PATH_CONFIG . 'autoload/{{,*.}global,{,*.}local}.php'),
            $providers[8]
        );
    }
}
