<?php
declare(strict_types=1);

namespace App\Domain\Wallet\VO;

use App\Domain\Common\JsonSerialize;
use App\Domain\DomainException\InvalidVO;
use JsonSerializable;

class Money implements JsonSerializable
{
    use JsonSerialize;

    private float $value;

    /**
     * Money constructor.
     *
     * @param float $value
     */
    public function __construct(float $value)
    {
        if ($value < 0) {
            throw new InvalidVO(self::class, strval($value));
        }

        $this->value = round($value, 2);
    }

    /**
     * @param  Money $money
     * @return Money
     */
    public function plus(Money $money): Money
    {
        return new Money($money->getValue() + $this->getValue());
    }

    /**
     * @param  Money $money
     * @return Money
     */
    public function minus(Money $money): Money
    {
        return new Money($this->getValue() - $money->getValue());
    }

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }
}
