<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\User\VO\Document;
use App\Domain\User\VO\Email;
use App\Domain\Common\VO\Identifier;
use App\Domain\DomainException\UserNotFoundException;

interface IUserRepository
{
    /**
     * @param User $user
     */
    public function save(User $user): void;

    /**
     * @param  Document $document
     * @param  Email    $email
     * @return User|null
     */
    public function findOneByDocumentOrEmail(Document $document, Email $email): ?User;

    /**
     * @param  Identifier $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findOneById(Identifier $id): User;
}
