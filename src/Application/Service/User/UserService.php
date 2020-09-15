<?php

namespace App\Application\Service\User;

use App\Domain\DomainException\UserAlreadyExistsException;
use App\Domain\User\IUserRepository;
use App\Domain\User\User;

final class UserService implements IUserService
{
    private IUserRepository $repository;

    /**
     * UserService constructor.
     *
     * @param IUserRepository $repository
     */
    public function __construct(IUserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param  User $new
     * @throws UserAlreadyExistsException
     */
    public function registerUser(User $new)
    {
        $this->checkUserAlreadyExists($new);

        $this->repository->save($new);

        /**
         * agora, enviar evento de dominio de userRegistered, dominio de wallet escutara esse
         * esse evento e ira criar a wallet para o usuario do lado dele
         */
    }

    /**
     * @param  User $new
     * @throws UserAlreadyExistsException
     */
    private function checkUserAlreadyExists(User $new): void
    {
        $user = $this->repository->findOneByDocumentOrEmail(
            $new->getDocument(),
            $new->getEmail()
        );

        if ($user) {
            throw new UserAlreadyExistsException();
        }
    }
}
