<?php
declare(strict_types=1);

namespace App\Application\Middleware;

use App\Application\Exceptions\HttpBadRequestException;
use App\Infrastructure\Validators\SchemaValidatorAdapter;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

final class PayloadValidationMiddleware implements Middleware
{
    private SchemaValidatorAdapter $validator;

    /**
     * PayloadValidationMiddleware constructor.
     * @param string $schemaPath
     */
    public function __construct(string $schemaPath)
    {
        $this->validator = new SchemaValidatorAdapter($schemaPath);
    }

    /**
     * {@inheritdoc}
     * @throws HttpBadRequestException
     */
    public function process(Request $request, RequestHandler $handler): Response
    {
        $payload = $request->getParsedBody();

        if (!$this->validator->validate($payload)) {
            throw new HttpBadRequestException($request, $this->validator->getErrors());
        }

        return $handler->handle($request);
    }
}
