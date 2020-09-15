<?php
declare(strict_types=1);

namespace App\Domain\Wallet\VO;

use App\Domain\Common\JsonSerialize;
use App\Domain\Common\VO\Email;
use App\Domain\Common\VO\Identifier;
use JsonSerializable;

class User implements JsonSerializable
{
    use JsonSerialize;

    private Identifier $id;

    private bool $isStore;

    /**
     * User constructor.
     *
     * @param Identifier $id
     * @param bool       $isStore
     */
    public function __construct(Identifier $id, bool $isStore)
    {
        $this->id = $id;
        $this->isStore = $isStore;
    }

    /**
     * @return Identifier
     */
    public function getId(): Identifier
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isStore(): bool
    {
        return $this->isStore;
    }
}
