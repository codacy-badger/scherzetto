<?php

namespace Lib\Db\Dbal;

use PDO;
use Symfony\Component\Yaml\Parser;

class Connection extends PDO
{
    const KEYS = ['host', 'port', 'dbname', 'unix_socket', 'charset'];

    public function __construct()
    {
        $parser = new Parser();
        $config = $parser->parseFile(__DIR__.'/../../../../config/config.yml')['database'];
        $params = $this->createParams($config);
        $password = getenv("TEST") ? '' : ($params['password'] ?? '');
        parent::__construct(
            $params['dsn'],
            $params['username'] ?? '',
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]
        );
    }


    private function createParams($config)
    {
        $params = [
            'dsn' => ($config['type'] ?? 'mysql').':'
        ];

        foreach ($config as $key => $val) {
            if (!\in_array($key, self::KEYS)) {
                if (\in_array($key, ['username', 'password'])) {
                    $params[$key] = $val;
                }
                unset($config[$key]);
            }
        }
        $params['dsn'] .= $this->createDsn($config);
        return $params;
    }

    private function createDsn($config)
    {
        $dsn = '';
        foreach (self::KEYS as $key) {
            if (isset($config[$key])) {
                $dsn .= "{$key}={$config[$key]};";
            }
        }
        return $dsn;
    }
}
