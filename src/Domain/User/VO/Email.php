<?php
declare(strict_types=1);

namespace App\Domain\User\VO;

use App\Domain\Common\JsonSerialize;
use App\Domain\DomainException\InvalidVO;
use JsonSerializable;

class Email implements JsonSerializable
{
    use JsonSerialize;

    private string $value;

    /**
     * Email constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL) || strlen($value) > 255) {
            throw new InvalidVO(self::class, $value);
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
}
