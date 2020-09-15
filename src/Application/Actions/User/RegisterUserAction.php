<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\User;
use App\Domain\User\VO\Document;
use App\Domain\User\VO\DocumentType;
use App\Domain\User\VO\Email;
use App\Domain\User\VO\FullName;
use App\Domain\Common\VO\Identifier;
use App\Domain\User\VO\Password;
use Psr\Http\Message\ResponseInterface as Response;

class RegisterUserAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $requestBody = $this->request->getParsedBody();

        $this->logger->info("user_register", $requestBody);
        $user = $this->createUserFromRequest();
        $this->service->registerUser($user);
        $this->logger->info("user_registered", $user->jsonSerialize());

        return $this->respondNoContent();
    }

    private function createUserFromRequest(): User
    {
        $requestBody = $this->request->getParsedBody();

        return new User(
            new Identifier($requestBody['id']),
            new FullName($requestBody['name']),
            new Document(
                $requestBody['document']['number'],
                new DocumentType($requestBody['document']['type'])
            ),
            new Email($requestBody['email']),
            new Password($requestBody['password'])
        );
    }
}
