<?php

namespace App\Infrastructure\Http;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class ClientAdapter
 *
 * @todo    implementar circuitbreaker, retry and fallback
 * @package App\Infrastructure\Http
 */
class ClientAdapter implements ClientInterface
{
    private Client $client;

    private LoggerInterface $logger;

    /**
     * ClientAdapter constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;

        $this->client = new Client(
            [
            'timeout' => 8,
            ]
        );
    }

    /**
     * @param  RequestInterface $request
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->logRequest($request);
        return $this->client->sendRequest($request);
    }

    /**
     * @param RequestInterface $request
     */
    private function logRequest(RequestInterface $request)
    {
        $this->logger->info(
            'sending request',
            [
                'method'  => $request->getMethod(),
                'headers' => $request->getHeaders(),
                'uri'     => $request->getUri(),
                'body'    => $request->getBody()
            ]
        );
    }
}
