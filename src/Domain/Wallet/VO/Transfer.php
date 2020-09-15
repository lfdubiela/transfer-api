<?php
declare(strict_types=1);

namespace App\Domain\Wallet\VO;

use App\Domain\Common\JsonSerialize;
use App\Domain\Common\VO\Identifier;
use JsonSerializable;

class Transfer implements JsonSerializable
{
    use JsonSerialize;

    private Identifier $payer;

    private Identifier $payee;

    private Money $value;

    /**
     * Transfer constructor.
     *
     * @param Identifier $payer
     * @param Identifier $payee
     * @param Money      $value
     */
    public function __construct(Identifier $payer, Identifier $payee, Money $value)
    {
        $this->payer = $payer;
        $this->payee = $payee;
        $this->value = $value;
    }

    /**
     * @return Identifier
     */
    public function getPayer(): Identifier
    {
        return $this->payer;
    }

    /**
     * @return Identifier
     */
    public function getPayee(): Identifier
    {
        return $this->payee;
    }

    /**
     * @return Money
     */
    public function getValue(): Money
    {
        return $this->value;
    }
}
