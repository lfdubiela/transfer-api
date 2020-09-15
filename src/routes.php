<?php
declare(strict_types=1);

use App\Application\Actions\User\RegisterUserAction;
use App\Application\Actions\Wallet\TransferAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use App\Application\Middleware\ContentTypeMiddleware;
use App\Application\Middleware\PayloadValidationMiddleware;

return function (App $app) {
    $app->options('/{routes:.*}',
        function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('My transfer API!');
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->post('/register', RegisterUserAction::class);
    });


    /**
     * @todo definir no settings e recuperar aqui
     */
    $schema = __DIR__ . '/Infrastructure/Validators/Schemas/transferV1.json';

    $app->post('/transfer', TransferAction::class)
        ->add(ContentTypeMiddleware::class)
        ->add(new PayloadValidationMiddleware($schema));
};
