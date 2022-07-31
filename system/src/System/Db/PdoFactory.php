<?php

declare(strict_types=1);

namespace Mobicms\System\Db;

use Devanych\Di\FactoryInterface;
use Mobicms\System\Db\Exception\InvalidDatabaseException;
use Mobicms\System\Db\Exception\InvalidCredentialsException;
use Mobicms\System\Db\Exception\UnableToConnectException;
use PDO;
use PDOException;
use Psr\Container\ContainerInterface;

class PdoFactory implements FactoryInterface
{
    public function create(ContainerInterface $container): PDO
    {
        $config = (array) $container->get('config');

        try {
            return new PDO(
                $this->prepareDsn($config),
                (string) ($config['database']['user'] ?? ''),
                (string) ($config['database']['pass'] ?? ''),
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $exception) {
            $code = (int) $exception->getCode();

            throw match ($code) {
                1045 => new InvalidCredentialsException('Invalid database password', $code),
                1049 => new InvalidDatabaseException('Invalid database credentials (user, password)', $code),
                2002 => new UnableToConnectException('Unable to connect to the database server', $code)
            };
        }
    }

    private function prepareDsn(array $config): string
    {
        /** @var string $host */
        $host = $config['database']['host'] ?? 'localhost';

        /** @var int $port */
        $port = $config['database']['port'] ?? 3306;

        /** @var string $name */
        $name = $config['database']['dbname'] ?? '';

        return sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $host, $port, $name);
    }
}
