<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Application\Actions\Action;
use App\Application\Service\User\IUserService;
use Psr\Log\LoggerInterface;

abstract class UserAction extends Action
{
    protected IUserService $service;

    public function __construct(LoggerInterface $logger, IUserService $service)
    {
        $this->service = $service;
        parent::__construct($logger);
    }
}
