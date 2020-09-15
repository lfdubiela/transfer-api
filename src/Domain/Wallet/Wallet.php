<?php
declare(strict_types=1);

namespace App\Domain\Wallet;

use App\Domain\Common\JsonSerialize;
use App\Domain\DomainException\InsufficientBalanceException;
use App\Domain\Wallet\VO\Money;
use App\Domain\Wallet\VO\User;
use JsonSerializable;

class Wallet implements JsonSerializable
{
    use JsonSerialize;

    private User $user;

    private Money $balance;

    /**
     * Wallet constructor.
     *
     * @param User  $user
     * @param Money $balance
     */
    public function __construct(User $user, Money $balance)
    {
        $this->user = $user;
        $this->balance = $balance;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Money
     */
    public function getBalance(): Money
    {
        return $this->balance;
    }

    /**
     * @param  Money $money
     * @return Wallet
     * @throws InsufficientBalanceException
     */
    public function debit(Money $money): Wallet
    {
        if (!$this->hasSufficientBalance($money)) {
            throw new InsufficientBalanceException();
        }

        return new Wallet(
            $this->user,
            $this->balance->minus($money)
        );
    }

    /**
     * @param  Money $money
     * @return Wallet
     */
    public function credit(Money $money): Wallet
    {
        return new Wallet(
            $this->user,
            $this->balance->plus($money)
        );
    }

    /**
     * @param  Money $money
     * @return bool
     */
    public function hasSufficientBalance(Money $money): bool
    {
        return $this->balance->getValue() >= $money->getValue();
    }
}
