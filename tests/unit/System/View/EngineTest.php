<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace MobicmsTest\System\View;

use Mobicms\System\View\Engine;
use PHPUnit\Framework\TestCase;

class EngineTest extends TestCase
{
    private Engine $engine;

    public function setUp(): void
    {
        $this->engine = new Engine();
        $this->engine->addPath(M_PATH_ROOT, 'test');
    }

    public function testGetPaths(): void
    {
        $this->assertIsArray($this->engine->getPaths());
    }

    public function testCanAddPath(): void
    {
        $this->engine->addPath(__DIR__, 'tmp');
        $paths = $this->engine->getFolder('tmp');
        $this->assertEquals(__DIR__, $paths[0]);
        $this->assertCount(1, $paths);
    }

    public function testRendering(): void
    {
        $var = 'mobiCMS';
        $result = $this->engine->render('test::tpl-1', ['var' => $var]);
        $this->assertEquals($var, $result);
    }

    public function testCanRenderWithoutArgument(): void
    {
        $result = $this->engine->render('test::tpl-null');
        $this->assertEquals('<h1>Null</h1>' . "\n", $result);
    }

    public function testAddSharedParameters(): void
    {
        $var = 'mobiCMS';
        $this->engine->addDefaultParam((string) $this->engine::TEMPLATE_ALL, 'var', $var);

        $this->assertEquals($var, $this->engine->render('test::tpl-1'));
        $this->assertEquals($var, $this->engine->render('test::tpl-2'));
    }

    public function testAddParameterToOneTemplate(): void
    {
        $var1 = 'test1';
        $var2 = 'test2';
        $this->engine->addDefaultParam('test::tpl-1', 'var', $var1);
        $this->engine->addDefaultParam('test::tpl-2', 'var', $var2);

        $this->assertEquals($var1, $this->engine->render('test::tpl-1'));
        $this->assertEquals($var2, $this->engine->render('test::tpl-2'));
    }

    public function testOverrideSharedParametersPerTemplate(): void
    {
        $var1 = 'test1';
        $var2 = 'test2';
        $this->engine->addDefaultParam((string) $this->engine::TEMPLATE_ALL, 'var', $var1);
        $this->engine->addDefaultParam('test::tpl-2', 'var', $var2);

        $this->assertEquals($var1, $this->engine->render('test::tpl-1'));
        $this->assertEquals($var2, $this->engine->render('test::tpl-2'));
    }

    public function testOverrideSharedParametersAtRender(): void
    {
        $var1 = 'test1';
        $var2 = 'test2';
        $this->engine->addDefaultParam((string) $this->engine::TEMPLATE_ALL, 'var', $var1);

        $this->assertEquals($var1, $this->engine->render('test::tpl-1'));
        $this->assertEquals($var2, $this->engine->render('test::tpl-2', ['var' => $var2]));
    }
}
