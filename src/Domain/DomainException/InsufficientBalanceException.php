<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class InsufficientBalanceException extends DomainException
{
    protected $message = 'User has insufficient balance to transfer this amount!';
}
