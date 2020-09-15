<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

return function (ContainerBuilder $containerBuilder) {
    // Global Settings Object
    $containerBuilder->addDefinitions(
        [
            'settings' => [
                'displayErrorDetails' => true, // Should be set to false in production
                'logger'              => [
                    'name'  => 'slim-app',
                    'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
                    'level' => Logger::DEBUG,
                ],

                'connection' => [
                    'host'     => 'wallet-mysql',
                    'port'     => 3306,
                    'user'     => 'root',
                    'password' => 'root',
                    'dbname'   => 'wallet-db',
                    'driver'   => 'pdo_mysql',
                    'charset'  => 'utf8',
                ],

                'schemas' => [
                    'transfer:V1' => __DIR__ . '/Infrastructure/Validators/Schemas/transferV1.json'
                ]
            ]
        ]
    );
};
