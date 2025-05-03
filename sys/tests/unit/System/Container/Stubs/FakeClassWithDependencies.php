<?php

declare(strict_types=1);

namespace MobicmsTest\System\Container\Stubs;

use Psr\Container\ContainerInterface;

class FakeClassWithDependencies
{
    private ContainerInterface $container;

    /** @phpstan-ignore constructor.unusedParameter */
    public function __construct(ContainerInterface $container, string $defaultValue = 'test')
    {
        $this->container = $container;
    }

    public function get(): mixed
    {
        return $this->container->get('foo');
    }
}
