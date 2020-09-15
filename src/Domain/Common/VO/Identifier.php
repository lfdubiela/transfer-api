<?php
declare(strict_types=1);

namespace App\Domain\Common\VO;

use App\Domain\Common\JsonSerialize;
use App\Domain\DomainException\InvalidVO;
use JsonSerializable;

class Identifier implements JsonSerializable
{
    use JsonSerialize;

    private int $value;

    /**
     * UserName constructor.
     *
     * @param $value
     */
    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new InvalidVO(self::class, strval($value));
        }

        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
