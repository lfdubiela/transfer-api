<?php

namespace App\Application\Service\User;

use App\Domain\User\User;

interface IUserService
{
    public function registerUser(User $user);
}
