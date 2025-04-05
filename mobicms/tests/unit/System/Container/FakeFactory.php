<?php

declare(strict_types=1);

namespace MobicmsTest\System\Container;

use ArrayObject;
use Psr\Container\ContainerInterface;

class FakeFactory
{
    public function __invoke(ContainerInterface $container): object
    {
        return new ArrayObject(['faketest' => 'fakestring']);
    }
}
