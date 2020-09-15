<?php
declare(strict_types=1);

namespace Tests\Domain\Wallet;

use App\Domain\Common\VO\Identifier;
use App\Domain\DomainException\InsufficientBalanceException;
use App\Domain\Wallet\VO\Money;
use App\Domain\Wallet\VO\User;
use App\Domain\Wallet\Wallet;
use Tests\TestCase;

class WalletTest extends TestCase
{
    public function walletProvider()
    {
        return [
            [1, true, 50.00],
            [2, false, 100.00],
            [3, true, 200.00],
            [3, true, 200.00],
        ];
    }

    /**
     * @dataProvider walletProvider
     * @param int $id
     * @param bool $isStore
     * @param float $balance
     */
    public function testGetters(int $id, bool $isStore, float $balance)
    {
        $wallet = new Wallet(
            new User(new Identifier($id), $isStore),
            new Money($balance)
        );

        $this->assertEquals($id, $wallet->getUser()->getId()->getValue());
        $this->assertEquals($isStore, $wallet->getUser()->isStore());
        $this->assertEquals($balance, $wallet->getBalance()->getValue());
    }

    /**
     * @dataProvider walletProvider
     * @param int $id
     * @param bool $isStore
     * @param float $balance
     */
    public function testJsonSerialize(int $id, bool $isStore, float $balance)
    {
        $wallet = new Wallet(
            new User(new Identifier($id), $isStore),
            new Money($balance)
        );

        $expectedPayload = json_encode([
            'user' => [
                'id' => $id,
                'isStore' => $isStore
            ],
            'balance' => $balance
        ]);

        $this->assertEquals($expectedPayload, json_encode($wallet));
    }


    public function testDebitInsufficientBalance()
    {
        $this->expectException(InsufficientBalanceException::class);

        $wallet = new Wallet(
            new User(new Identifier(1), false),
            new Money(50)
        );

        $wallet->debit(new Money(51));
    }

    public function testDebit()
    {
        $wallet = new Wallet(
            new User(new Identifier(1), false),
            new Money(50)
        );

        $wallet = $wallet->debit(new Money(50));

        $this->assertEquals(new Money(0), $wallet->getBalance());
    }

    public function testCredit()
    {
        $wallet = new Wallet(
            new User(new Identifier(1), false),
            new Money(50)
        );

        $wallet = $wallet->credit(new Money(50));

        $this->assertEquals(new Money(100), $wallet->getBalance());
    }
}
