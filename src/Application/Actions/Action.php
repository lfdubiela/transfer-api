<?php
declare(strict_types=1);

namespace App\Application\Actions;

use App\Application\Exceptions\HttpUnprocessableEntityException;
use App\Domain\DomainException\DomainException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Log\LoggerInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;

abstract class Action
{
    protected LoggerInterface $logger;

    protected Request $request;

    protected Response $response;

    protected array $args;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param  Request  $request
     * @param  Response $response
     * @param  $args
     * @return Response
     * @throws HttpBadRequestException
     * @throws HttpUnprocessableEntityException
     */
    public function __invoke(Request $request, Response $response, $args): Response
    {
        $this->request = $request;
        $this->response = $response;
        $this->args = $args;

        try {
            return $this->action();
        } catch (DomainException $e) {
            throw new HttpUnprocessableEntityException($this->request, $e->getMessage());
        }
    }

    /**
     * @return Response
     * @throws HttpUnprocessableEntityException
     * @throws HttpBadRequestException
     */
    abstract protected function action(): Response;

    /**
     * @return array|object
     * @throws HttpBadRequestException
     */
    protected function getFormData()
    {
        $input = json_decode(file_get_contents('php://input'));

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpBadRequestException($this->request, 'Malformed JSON input.');
        }

        return $input;
    }

    /**
     * @param  string $name
     * @return mixed
     * @throws HttpBadRequestException
     */
    protected function resolveArg(string $name)
    {
        if (!isset($this->args[$name])) {
            throw new HttpBadRequestException($this->request, "Could not resolve argument `{$name}`.");
        }

        return $this->args[$name];
    }

    /**
     * @param  array|null $data
     * @param  int        $statusCode
     * @return Response
     */
    protected function respondWithData(array $data = null, int $statusCode = 200): Response
    {
        $payload = new ActionPayload($statusCode, $data);

        return $this->respond($payload);
    }

    protected function respondNoContent(): Response
    {
        return $this->respond(new ActionPayload(204));
    }


    /**
     * @param  ActionPayload $payload
     * @return Response
     */
    protected function respond(ActionPayload $payload): Response
    {
        if ($payload->getStatusCode() != 204) {
            $json = json_encode($payload, JSON_PRETTY_PRINT);

            $this->response->getBody()->write($json);
            $this->response->withHeader('Content-Type', 'application/json');
        }

        return $this->response
            ->withStatus($payload->getStatusCode());
    }
}
