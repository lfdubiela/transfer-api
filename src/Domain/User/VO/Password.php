<?php
declare(strict_types=1);

namespace App\Domain\User\VO;

use App\Domain\DomainException\InvalidVO;
use JsonSerializable;

class Password implements JsonSerializable
{
    private string $value;

    const OBFUSCATED_VALUE = "*******";

    /**
     * UserName constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (strlen($value) > 32) {
            throw new InvalidVO(self::class, self::OBFUSCATED_VALUE);
        }

        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Returning a obfuscated password
     *
     * @return mixed|string
     */
    public function jsonSerialize()
    {
        return self::OBFUSCATED_VALUE;
    }
}
