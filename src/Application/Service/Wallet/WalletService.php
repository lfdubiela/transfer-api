<?php

namespace App\Application\Service\Wallet;

use App\Domain\DomainException\InsufficientBalanceException;
use App\Domain\DomainException\StoreCannotMakeTransferException;
use App\Domain\DomainException\TransferNotAuthorizedException;
use App\Domain\DomainException\WalletNotFoundException;
use App\Domain\Wallet\IWalletRepository;
use App\Domain\Wallet\VO\Transfer;
use App\Infrastructure\Http\Services\TransferNotifierService;
use App\Infrastructure\Http\Services\TransferAuthorizerService;

final class WalletService implements IWalletService
{
    private IWalletRepository $repository;

    private TransferAuthorizerService $authorizer;

    private TransferNotifierService $notifier;

    /**
     * WalletService constructor.
     *
     * @param IWalletRepository         $walletRepository
     * @param TransferAuthorizerService $authorizer
     * @param TransferNotifierService   $notifier
     */
    public function __construct(
        IWalletRepository $walletRepository,
        TransferAuthorizerService $authorizer,
        TransferNotifierService $notifier
    ) {
        $this->repository = $walletRepository;
        $this->authorizer = $authorizer;
        $this->notifier = $notifier;
    }

    /**
     * @param Transfer $transfer
     *
     * @throws WalletNotFoundException
     * @throws StoreCannotMakeTransferException
     * @throws TransferNotAuthorizedException
     * @throws InsufficientBalanceException
     */
    public function transfer(Transfer $transfer)
    {
        if (!$this->authorizeTransfer($transfer)) {
            throw new TransferNotAuthorizedException();
        }

        $this->repository->transfer($transfer);
        $this->notifier->notify($transfer);
    }

    /**
     * @param  Transfer $transfer
     * @return bool
     */
    private function authorizeTransfer(Transfer $transfer): bool
    {
        return $this->authorizer->authorize($transfer);
    }
}
