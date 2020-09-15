<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class UserNotFoundException extends DomainException
{
    protected $message = 'The user requested does not exist!';
}
