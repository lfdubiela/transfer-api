<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class WalletNotFoundException extends DomainException
{
    protected $message = 'The wallet requested does not exist!';
}
