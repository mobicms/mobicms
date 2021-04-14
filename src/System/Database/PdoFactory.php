<?php

/**
 * This file is part of mobicms/mobicms package
 *
 * @see       https://github.com/mobicms/mobicms for the canonical source repository
 * @license   https://github.com/mobicms/mobicms/blob/develop/LICENSE GPL-3.0
 * @copyright https://github.com/mobicms/mobicms/blob/develop/README.md
 */

declare(strict_types=1);

namespace Mobicms\System\Database;

use PDO;
use Psr\Container\ContainerInterface;

class PdoFactory
{
    public function __invoke(ContainerInterface $container): PDO
    {
        $config = $container->has('database')
            ? (array) $container->get('database')
            : [];

        return new PDO(
            $this->prepareDsn($config),
            $config['user'] ?? null,
            $config['pass'] ?? null,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    private function prepareDsn(array $config): string
    {
        if (! empty($config['dsn'])) {
            return $config['dsn'];
        }

        $host = ! empty($config['host']) ? $config['host'] : 'localhost';
        $port = ! empty($config['port']) ? ';port=' . $config['port'] : '';
        $dbname = ! empty($config['dbname']) ? $config['dbname'] : '';
        return 'mysql:host=' . $host . $port . ';dbname=' . $dbname . ';charset=utf8mb4';
    }
}
