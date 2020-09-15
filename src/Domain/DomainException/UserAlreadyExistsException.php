<?php
declare(strict_types=1);

namespace App\Domain\DomainException;

class UserAlreadyExistsException extends DomainException
{
    protected $message = 'The user informed already exists!';
}
