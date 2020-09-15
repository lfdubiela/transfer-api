<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class InvalidVO extends \InvalidArgumentException
{
    /**
     * InvalidVO constructor.
     *
     * @param string $class
     * @param string $value
     */
    public function __construct(string $class, string $value)
    {
        $this->message = "Invalid value for class {$class}(value={$value})";
        parent::__construct();
    }
}
