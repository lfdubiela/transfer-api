<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Wallet;

use App\Domain\Common\VO\Identifier;
use App\Domain\DomainException\WalletNotFoundException;
use App\Domain\Wallet\IWalletRepository;
use App\Domain\Wallet\VO\Money;
use App\Domain\Wallet\VO\User;
use App\Domain\Wallet\Wallet;
use App\Infrastructure\Http\Services\TransferAuthorizerService;
use App\Infrastructure\Http\Services\TransferNotifierService;
use App\Infrastructure\Persistence\Wallet\WalletRepository;

trait WalletActionTest
{
    private function setUpDependencies(array $options = []): void
    {
        $authorize = $options['authorize'] ?? true;

        $container = $this->getAppInstance()->getContainer();
        $container->set(IWalletRepository::class, $this->getWalletRepositoryMock());

        $container->set(TransferAuthorizerService::class, $this->getAuthorizerMock($authorize));
        $container->set(TransferNotifierService::class, $this->getNotifierMock());
    }

    protected function getNotifierMock(): TransferNotifierService
    {
        $mock = $this->createPartialMock(TransferNotifierService::class,
            ['notify']
        );

        $mock->method('notify')
            ->willReturnCallback(function() {});

        return $mock;
    }

    protected function getAuthorizerMock(bool $authorizer = true): TransferAuthorizerService
    {
        $mock = $this->createPartialMock(TransferAuthorizerService::class,
            ['authorize']
        );

        $mock->method('authorize')
            ->willReturn($authorizer);

        return $mock;
    }

    protected function getWalletRepositoryMock(): WalletRepository
    {
        $mock = $this->createPartialMock(WalletRepository::class,
            ['findOneById', 'executeTransfer']
        );

        $wallets = [
            1 => new Wallet(new User(new Identifier(1), true), new Money(50.00)),
            2 => new Wallet(new User(new Identifier(2), false), new Money(20.00)),
            3 => new Wallet(new User(new Identifier(3), false), new Money(15.00)),
        ];

        $mock->method('findOneById')
            ->willReturnCallback(function($id) use ($wallets) {
                $wallet = isset($wallets[$id->getValue()])
                    ? $wallets[$id->getValue()]
                    : null;

                if (!$wallet) {
                    throw new WalletNotFoundException();
                }

                return $wallet;
            });

        $mock->method('executeTransfer')
            ->willReturnCallback(function() {});

        return $mock;
    }
}