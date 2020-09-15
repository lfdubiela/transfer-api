<?php
declare(strict_types=1);

namespace App;

use App\Application\Handlers\HttpErrorHandler;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Slim\Factory\ServerRequestCreatorFactory;

/**
 * Class App
 *
 * @package App
 */
class App
{
    /**
     * @var \Slim\App
     */
    private $app;

    /**
     * App constructor.
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     */
    public function __construct()
    {
        $container = $this->buildContainer();

        // Instantiate the app
        AppFactory::setContainer($container);
        $this->app = AppFactory::create();

        $this->registerMiddlewares();

        $this->registerRoutes();

        $this->app->addRoutingMiddleware();

        $this->app->addBodyParsingMiddleware();

        $this->addErrorMiddleware($container);
    }

    /**
     * @codeCoverageIgnore
     */
    public function run(): void
    {
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();
        $this->app->run($request);
    }

    /**
     * @return ContainerInterface
     * @throws \Exception
     */
    private function buildContainer(): ContainerInterface
    {
        // Instantiate PHP-DI ContainerBuilder
        $containerBuilder = new ContainerBuilder();

        // Set up settings
        $settings = include __DIR__ . '/settings.php';
        $settings($containerBuilder);

        // Set up dependencies
        $dependencies = include __DIR__ . '/dependencies.php';
        $dependencies($containerBuilder);

        return $containerBuilder->build();
    }

    private function registerRoutes(): void
    {
        $routes = include __DIR__ . '/routes.php';
        $routes($this->app);
    }

    private function registerMiddlewares(): void
    {
        $middleware = include __DIR__ . '/middleware.php';
        $middleware($this->app);
    }

    /**
     * @param ContainerInterface $container
     */
    private function addErrorMiddleware(ContainerInterface $container): void
    {
        /**
         * @var bool $displayErrorDetails
         */
        $displayErrorDetails = $container->get('settings')['displayErrorDetails'];

        // Create Error Handler
        $callableResolver = $this->app->getCallableResolver();
        $responseFactory = $this->app->getResponseFactory();
        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

        // Add Error Middleware
        $errorMiddleware = $this->app->addErrorMiddleware($displayErrorDetails, false, false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);
    }


    /**
     * @return ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->app->getContainer();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->app->handle($request);
    }
}
