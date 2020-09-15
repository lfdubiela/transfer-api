<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Services\Exceptions;

class InvalidResponseException extends HttpServiceException
{
    protected $message = "Request with invalid returned!";
}
