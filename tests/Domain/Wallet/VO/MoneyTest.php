<?php
declare(strict_types=1);

namespace Tests\Domain\Wallet\VO;

use App\Domain\Common\VO\Identifier;
use App\Domain\DomainException\InsufficientBalanceException;
use App\Domain\DomainException\InvalidVO;
use App\Domain\Wallet\VO\Money;
use App\Domain\Wallet\VO\User;
use App\Domain\Wallet\Wallet;
use Tests\TestCase;

class MoneyTest extends TestCase
{
    public function moneyProvider()
    {
        return [
            [1.00, true],
            [100.122, true],
            [10.01, true],
            [1000.10, true],
            [-122.10, false],
        ];
    }

    /**
     * @dataProvider moneyProvider
     * @param float $value
     * @param bool $valid
     */
    public function testValues(float $value, bool $valid)
    {
        if (!$valid) {
            $this->expectException(InvalidVO::class);
            new Money($value);
        } else {
            $money = new Money($value);
            $this->assertEquals(round($value, 2), $money->getValue());
        }
    }
}
