<?php
declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Actions\ActionPayload;
use App\Application\Exceptions\HttpBadRequestException;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Exception\HttpException;
use Slim\Handlers\ErrorHandler as SlimErrorHandler;

class HttpErrorHandler extends SlimErrorHandler
{
    /**
     * @inheritdoc
     */
    protected function respond(): Response
    {
        $exception = $this->exception;

        $payload = new ActionPayload(
            500,
            [
            'code'        => '500',
            'description' => $exception->getTrace()
            ]
        );

        if ($exception instanceof HttpException) {
            $payload = new ActionPayload(
                $exception->getCode(),
                [
                    'code'        => $exception->getCode(),
                    'description' => $exception->getMessage()
                ]
            );
        }

        if ($exception instanceof HttpBadRequestException) {
            $payload = new ActionPayload(
                $exception->getCode(),
                $exception->getErrors()
            );
        }

        $encodedPayload = json_encode($payload, JSON_PRETTY_PRINT);
        $response = $this->responseFactory->createResponse($payload->getStatusCode());

        if ($payload->getStatusCode() >= 100 && $payload->getStatusCode() <= 499) {
            $response->getBody()->write($encodedPayload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}
