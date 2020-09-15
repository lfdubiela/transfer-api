<?php

namespace App\Application\Exceptions;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;

class HttpBadRequestException extends HttpException
{
    protected $code = 400;
    protected $message = 'Bad Request.';
    protected $title = '400 Bad Request';
    protected $description = 'Invalid body/payload';
    protected array $errors;

    /**
     * HttpBadRequestException constructor.
     * @param ServerRequestInterface $request
     * @param array $errors
     */
    public function __construct(ServerRequestInterface $request, array $errors)
    {
        $this->errors = $errors;
        parent::__construct($request, $this->message, $this->code);
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
