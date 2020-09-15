<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use App\Domain\User\IUserRepository;
use App\Application\Service\User\IUserService;
use App\Application\Service\User\UserService;
use App\Infrastructure\Persistence\Wallet\WalletRepository;
use App\Infrastructure\Http\Services\TransferAuthorizerService;
use App\Infrastructure\Http\Services\TransferNotifierService;
use App\Application\Service\Wallet\IWalletService;
use App\Application\Service\Wallet\WalletService;
use App\Domain\Wallet\IWalletRepository;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        Connection::class => function (ContainerInterface $c) {
            $settings = $c->get('settings')['connection'];
            return DriverManager::getConnection($settings);
        },

        IUserRepository::class => \DI\autowire(WalletRepository::class),
        IUserService::class    => \DI\autowire(UserService::class),

        IWalletService::class    => \DI\autowire(WalletService::class),
        IWalletRepository::class => \DI\autowire(WalletRepository::class),

        TransferAuthorizerService::class => \DI\autowire(TransferAuthorizerService::class),
        TransferNotifierService::class   => \DI\autowire(TransferNotifierService::class),
    ]);
};
