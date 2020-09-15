<?php

namespace App\Application\Exceptions;

use Slim\Exception\HttpException;

class HttpUnprocessableEntityException extends HttpException
{
    protected $code = 422;
    protected $message = 'Unprocessable Entity.';
    protected $title = '422 Unprocessable Entity';
    protected $description = 'The entity could not be processed';
}
