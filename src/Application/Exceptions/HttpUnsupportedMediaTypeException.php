<?php

namespace App\Application\Exceptions;

use Slim\Exception\HttpException;

class HttpUnsupportedMediaTypeException extends HttpException
{
    protected $code = 415;
    protected $message = 'Unsupported Media Type.';
    protected $title = '415 Unsupported Media Type';
    protected $description = 'The requested content-type or content-encoding were refused by the server.';
}
