<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class StoreCannotMakeTransferException extends DomainException
{
    protected $message = 'Store cannot make transfer!';
}
