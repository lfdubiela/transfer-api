<?php
declare(strict_types=1);

namespace Tests\Application\Middleware;

use App\Application\Exceptions\HttpUnsupportedMediaTypeException;
use App\Application\Middleware\ContentTypeMiddleware;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Tests\Helpers\TransferHelper;
use Tests\TestCase;

/**
 * Class ContentTypeMiddlewareTest
 * @package Tests\Application\Middleware
 */
class ContentTypeMiddlewareTest extends TestCase
{
    public function testSuccess()
    {
        $prophecy = $this->prophesize(RequestHandlerInterface::class);
        $prophecy->handle(Argument::type(ServerRequestInterface::class))
            ->willReturn((new ResponseFactory())->createResponse());
        $mockRequestHandlerNext = $prophecy->reveal();

        $request = $this->createRequest(
            'POST',
            '/transfer',
            ['Content-Type' => 'application/json'],
            TransferHelper::getPayload()
        );

        $middleware = new ContentTypeMiddleware();
        $response = $middleware->process($request, $mockRequestHandlerNext);

        self::assertInstanceOf(ResponseInterface::class, $response);
    }

    public function testMissingContentTypeError()
    {
        $this->expectException(HttpUnsupportedMediaTypeException::class);

        $prophecy = $this->prophesize(RequestHandlerInterface::class);
        $prophecy->handle(Argument::type(ServerRequestInterface::class))
            ->willReturn((new ResponseFactory())->createResponse());
        $mockRequestHandlerNext = $prophecy->reveal();

        $request = $this->createRequest(
            'POST',
            '/transfer',
            [],
            TransferHelper::getPayload()
        );

        $middleware = new ContentTypeMiddleware();

        $middleware->process($request, $mockRequestHandlerNext);
    }

    public function testInvalidContentTypeError()
    {
        $this->expectException(HttpUnsupportedMediaTypeException::class);

        $prophecy = $this->prophesize(RequestHandlerInterface::class);
        $prophecy->handle(Argument::type(ServerRequestInterface::class))
            ->willReturn((new ResponseFactory())->createResponse());
        $mockRequestHandlerNext = $prophecy->reveal();

        $request = $this->createRequest(
            'POST',
            '/transfer',
            ['Content-Type' => 'application/x-www-form-urlencoded'],
            TransferHelper::getPayload()
        );

        $middleware = new ContentTypeMiddleware();

        $middleware->process($request, $mockRequestHandlerNext);
    }
}
