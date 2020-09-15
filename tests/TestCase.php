<?php
declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Headers;
use Slim\Psr7\Request as SlimRequest;
use Slim\Psr7\Uri;
use App\App;

class TestCase extends PHPUnit_TestCase
{
    protected ?App $app = null;

    protected function getAppInstance(): App
    {
        if (!$this->app) {
            $this->app = new App();
        }

        return $this->app;
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $headers
     * @param array|null $body
     * @return Request
     */
    protected function createRequest(
        string $method,
        string $path,
        array $headers = [],
        array $body = null
    ): Request {
        $uri = new Uri('', '', 80, $path);
        $handle = fopen('php://temp', 'w+');
        fwrite($handle, json_encode($body));
        $stream = (new StreamFactory())->createStreamFromResource($handle);

        $h = new Headers();
        foreach ($headers as $name => $value) {
            $h->addHeader($name, $value);
        }

        return new SlimRequest($method, $uri, $h, [], [], $stream);
    }
}
