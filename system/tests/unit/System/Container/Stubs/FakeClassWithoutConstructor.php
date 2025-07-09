<?php

declare(strict_types=1);

namespace MobicmsTest\System\Container\Stubs;

class FakeClassWithoutConstructor
{
    public function get(): string
    {
        return 'test';
    }
}
