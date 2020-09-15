<?php
declare(strict_types=1);

namespace App\Application\Actions;

use JsonSerializable;

class ActionPayload implements JsonSerializable
{
    private int $statusCode;

    private ?array $data;

    public function __construct(
        int $statusCode = 200,
        array $data = null
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }


    public function getData(): ?array
    {
        return $this->data;
    }

    public function jsonSerialize(): array
    {
        if ($this->data !== null) {
            return $this->data;
        }

        return [];
    }
}
