<?php
declare(strict_types=1);

namespace App\Application\Actions\Wallet;

use App\Application\Actions\Action;
use App\Application\Service\Wallet\IWalletService;
use Psr\Log\LoggerInterface;

abstract class WalletAction extends Action
{
    protected IWalletService $service;

    public function __construct(LoggerInterface $logger, IWalletService $service)
    {
        $this->service = $service;
        parent::__construct($logger);
    }
}
