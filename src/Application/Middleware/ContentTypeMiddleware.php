<?php

namespace App\Application\Middleware;

use App\Application\Exceptions\HttpUnsupportedMediaTypeException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class ContentTypeMiddleware implements Middleware
{
    /**
     * {@inheritdoc}
     *
     * @throws HttpUnsupportedMediaTypeException
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        if (count($request->getHeader('Content-Type')) < 1) {
            throw new HttpUnsupportedMediaTypeException($request);
        }

        if (!preg_match('/^application\/json(;+.*)*$/', $request->getHeader('Content-Type')[0])) {
            throw new HttpUnsupportedMediaTypeException($request);
        }

        return $handler->handle($request);
    }
}
