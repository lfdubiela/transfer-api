<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Services;

use App\Infrastructure\Http\ClientAdapter;
use Psr\Http\Client\ClientInterface;
use Psr\Log\LoggerInterface;

abstract class Service
{
    private ClientInterface $client;

    protected LoggerInterface $logger;

    /**
     * Service constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->client = new ClientAdapter($logger);
    }

    /**
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }
}
