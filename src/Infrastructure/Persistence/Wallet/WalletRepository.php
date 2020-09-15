<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Wallet;

use App\Domain\Common\VO\Identifier;
use App\Domain\DomainException\InsufficientBalanceException;
use App\Domain\DomainException\StoreCannotMakeTransferException;
use App\Domain\DomainException\WalletNotFoundException;
use App\Domain\Wallet\VO\Money;
use App\Domain\Wallet\IWalletRepository;
use App\Domain\Wallet\VO\Transfer;
use App\Domain\Wallet\Wallet;
use App\Infrastructure\Repository\Repository;
use App\Domain\Wallet\VO\User;
use Doctrine\DBAL\ConnectionException;
use Exception;

class WalletRepository extends Repository implements IWalletRepository
{
    /**
     * @param  Transfer $transfer
     * @throws InsufficientBalanceException
     * @throws WalletNotFoundException
     * @throws StoreCannotMakeTransferException
     * @throws ConnectionException
     */
    public function transfer(Transfer $transfer): void
    {
        $payerWallet = $this->findOneById($transfer->getPayer());

        $this->checkPreRequirementsForTransfer($payerWallet, $transfer);

        $payeeWallet = $this->findOneById($transfer->getPayee());

        $this->executeTransfer($payerWallet, $transfer, $payeeWallet);
    }

    /**
     * @param  Wallet   $payerWallet
     * @param  Transfer $transfer
     * @param  Wallet   $payeeWallet
     * @throws ConnectionException
     */
    protected function executeTransfer(
        Wallet $payerWallet,
        Transfer $transfer,
        Wallet $payeeWallet
    ): void {
        $this->getConnection()->beginTransaction();

        try {
            $payerWallet = $payerWallet->debit($transfer->getValue());
            $payeeWallet = $payeeWallet->credit($transfer->getValue());

            $this->updateBalance($payerWallet);
            $this->updateBalance($payeeWallet);

            $this->getConnection()->commit();
        } catch (Exception $e) {
            $this->getConnection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param Wallet $wallet
     */
    private function updateBalance(Wallet $wallet)
    {
        $this->getConnection()->createQueryBuilder()
            ->update("wallet")
            ->set("balance", $wallet->getBalance()->getValue())
            ->where("id = ?")
            ->setParameter(0, $wallet->getUser()->getId()->getValue())
            ->execute();
    }

    /**
     * @param  Identifier $id
     * @return Wallet
     * @throws WalletNotFoundException
     */
    public function findOneById(Identifier $id): Wallet
    {
        $data = $this->getConnection()->createQueryBuilder()
            ->select(
                [
                'w.id w_id',
                'w.balance w_balance',
                'u.is_store u_isStore',
                ]
            )
            ->from('wallet', 'w')
            ->join('w', 'user', 'u', 'u.id = w.id')
            ->where('u.id = ?')
            ->setParameter(0, $id->getValue())
            ->execute()
            ->fetch();

        if (!$data) {
            throw new WalletNotFoundException();
        }

        return $this->instanceWalletFromRecord($data);
    }

    /**
     * @param  array $data
     * @return Wallet
     */
    private function instanceWalletFromRecord(array $data): Wallet
    {
        return new Wallet(
            new User(
                new Identifier((int) $data['w_id']),
                (bool) $data['u_isStore']
            ),
            new Money((float) $data['w_balance'])
        );
    }

    /**
     * @param  Wallet   $payerWallet
     * @param  Transfer $transfer
     * @throws InsufficientBalanceException
     * @throws StoreCannotMakeTransferException
     */
    private function checkPreRequirementsForTransfer(Wallet $payerWallet, Transfer $transfer): void
    {
        if ($payerWallet->getUser()->isStore()) {
            throw new StoreCannotMakeTransferException();
        }

        if (!$payerWallet->hasSufficientBalance($transfer->getValue())) {
            throw new InsufficientBalanceException();
        }
    }
}
