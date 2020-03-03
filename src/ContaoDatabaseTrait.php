<?php

declare(strict_types=1);

/*
 * This file is part of Contao.
 *
 * (c) Leo Feyer
 *
 * @license LGPL-3.0-or-later
 */

namespace Contao\TestCase;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

trait ContaoDatabaseTrait
{
    /**
     * @var Connection
     */
    private static $connection;

    protected static function loadFileIntoDatabase(string $sqlFile): void
    {
        if (!file_exists($sqlFile)) {
            throw new \InvalidArgumentException(sprintf('File "%s" does not exist', $sqlFile));
        }

        $conn = static::getConnection();
        $conn->exec(file_get_contents($sqlFile));
    }

    protected static function getConnection(): Connection
    {
        if (null === self::$connection) {
            if (false !== getenv('DATABASE_URL')) {
                $params = [
                    'driver' => 'pdo_mysql',
                    'url' => getenv('DATABASE_URL'),
                ];
            } else {
                $params = [
                    'driver' => 'pdo_mysql',
                    'host' => getenv('DB_HOST'),
                    'port' => getenv('DB_PORT'),
                    'user' => getenv('DB_USER'),
                    'password' => getenv('DB_PASS'),
                    'dbname' => getenv('DB_NAME'),
                ];
            }

            self::$connection = DriverManager::getConnection($params);
        }

        return self::$connection;
    }
}
