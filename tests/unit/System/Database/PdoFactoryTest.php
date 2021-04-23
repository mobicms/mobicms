<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace MobicmsTest\System\Database;

use Mobicms\System\Database\Exception\CommonException;
use Mobicms\System\Database\Exception\InvalidCredentialsException;
use Mobicms\System\Database\Exception\InvalidDatabaseException;
use Mobicms\System\Database\Exception\MissingConfigException;
use Mobicms\System\Database\Exception\UnableToConnectException;
use Mobicms\System\Database\PdoFactory;
use MobicmsTest\DatabaseConnectionTrait;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use PDO;
use Psr\Container\ContainerInterface;

class PdoFactoryTest extends MockeryTestCase
{
    use DatabaseConnectionTrait;

    private Mockery\MockInterface $container;

    public function setUp(): void
    {
        if (null === self::$pdo) {
            $this->markTestSkipped('Need database to test.');
        }

        $this->container = Mockery::mock(ContainerInterface::class);
        $this->container
            ->shouldReceive('has')
            ->with('database')
            ->andReturn(true);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testFactoryReturnsInstanceOfPdo(): void
    {
        $this->container
            ->shouldReceive('get')
            ->with('database')
            ->andReturn(
                [
                    'host'   => self::$dbHost,
                    'port'   => self::$dbPort,
                    'dbname' => self::$dbName,
                    'user'   => self::$dbUser,
                    'pass'   => self::$dbPass,
                ]
            );

        $factory = (new PdoFactory())($this->container);
        $this->assertInstanceOf(PDO::class, $factory);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testFactoryReturnsInstanceOfPdoUsingDsn(): void
    {
        $this->container
            ->shouldReceive('get')
            ->with('database')
            ->andReturn(
                [
                    'dsn'  => self::$dsn,
                    'user' => self::$dbUser,
                    'pass' => self::$dbPass,
                ]
            );

        $factory = (new PdoFactory())($this->container);
        $this->assertInstanceOf(PDO::class, $factory);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testInvalidPasswordThrowInvalidCredentialsException(): void
    {
        $this->container
            ->shouldReceive('get')
            ->with('database')
            ->andReturn(
                [
                    'dsn'  => self::$dsn,
                    'user' => self::$dbUser,
                    'pass' => 'invalid_password',
                ]
            );

        $this->expectException(InvalidCredentialsException::class);
        (new PdoFactory())($this->container);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testInvalidUserThrowInvalidCredentialsException(): void
    {
        $this->container
            ->shouldReceive('get')
            ->with('database')
            ->andReturn(
                [
                    'dsn'  => self::$dsn,
                    'user' => 'invalid_user',
                    'pass' => self::$dbPass,
                ]
            );

        $this->expectException(InvalidCredentialsException::class);
        (new PdoFactory())($this->container);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testInvalidDatabaseNameThrowInvalidDatabaseException(): void
    {
        $this->container
            ->shouldReceive('get')
            ->with('database')
            ->andReturn(
                [
                    'host'   => self::$dbHost,
                    'port'   => self::$dbPort,
                    'dbname' => 'invalid_database',
                    'user'   => self::$dbUser,
                    'pass'   => self::$dbPass,
                ]
            );

        $this->expectException(InvalidDatabaseException::class);
        (new PdoFactory())($this->container);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testInvalidHostThrowUnableToConnectException(): void
    {
        $this->container
            ->shouldReceive('get')
            ->with('database')
            ->andReturn(
                [
                    'host'   => 'invalid_host',
                    'port'   => self::$dbPort,
                    'dbname' => self::$dbName,
                    'user'   => self::$dbUser,
                    'pass'   => self::$dbPass,
                ]
            );

        $this->expectException(UnableToConnectException::class);
        (new PdoFactory())($this->container);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testInvalidPortThrowUnableToConnectException(): void
    {
        $this->container
            ->shouldReceive('get')
            ->with('database')
            ->andReturn(
                [
                    'host'   => self::$dbHost,
                    'port'   => 9999999999,
                    'dbname' => self::$dbName,
                    'user'   => self::$dbUser,
                    'pass'   => self::$dbPass,
                ]
            );

        $this->expectException(UnableToConnectException::class);
        (new PdoFactory())($this->container);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testInvalidDsnThrowCommonException(): void
    {
        $this->container
            ->shouldReceive('get')
            ->with('database')
            ->andReturn(
                [
                    'dsn'  => 'invalid_dsn',
                    'user' => self::$dbUser,
                    'pass' => self::$dbPass,
                ]
            );

        $this->expectException(CommonException::class);
        (new PdoFactory())($this->container);
    }

    /**
     * @psalm-suppress InvalidArgument
     */
    public function testMissingConfigThrowMissingConfigException(): void
    {
        $container = Mockery::mock(ContainerInterface::class);
        $container
            ->shouldReceive('has')
            ->with('database')
            ->andReturn(false);
        $this->expectException(MissingConfigException::class);
        (new PdoFactory())($container);
    }
}
