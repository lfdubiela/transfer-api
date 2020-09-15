<?php

namespace App\Application\Service\Wallet;

use App\Domain\Wallet\VO\Transfer;

interface IWalletService
{
    public function transfer(Transfer $user);
}
