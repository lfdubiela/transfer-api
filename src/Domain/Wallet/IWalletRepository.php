<?php
declare(strict_types=1);

namespace App\Domain\Wallet;

use App\Domain\Common\VO\Identifier;
use App\Domain\DomainException\InsufficientBalanceException;
use App\Domain\DomainException\StoreCannotMakeTransferException;
use App\Domain\DomainException\WalletNotFoundException;
use App\Domain\Wallet\VO\Transfer;
use App\Infrastructure\Repository\IRepository;

interface IWalletRepository extends IRepository
{
    /**
     * @param  Identifier $id
     * @return Wallet
     * @throws WalletNotFoundException
     */
    public function findOneById(Identifier $id): Wallet;

    /**
     * @param  Transfer $transfer
     * @throws InsufficientBalanceException
     * @throws WalletNotFoundException
     * @throws StoreCannotMakeTransferException
     */
    public function transfer(Transfer $transfer): void;
}
