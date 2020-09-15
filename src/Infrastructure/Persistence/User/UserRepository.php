<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\DomainException\UserNotFoundException;
use App\Domain\User\User;
use App\Domain\User\IUserRepository;
use App\Domain\User\VO\Document;
use App\Domain\User\VO\DocumentType;
use App\Domain\User\VO\Email;
use App\Domain\User\VO\FullName;
use App\Domain\Common\VO\Identifier;
use App\Domain\User\VO\Password;
use App\Infrastructure\Repository\Repository;

class UserRepository extends Repository implements IUserRepository
{
    /**
     * @param User $user
     */
    public function save(User $user): void
    {
        $this->getConnection()->createQueryBuilder()
            ->insert('user')
            ->values(
                [
                'id'              => '?',
                'name'            => '?',
                'document_number' => '?',
                'document_type'   => '?',
                'email'           => '?',
                'password'        => '?'
                ]
            )
            ->setParameter(0, $user->getId()->getValue())
            ->setParameter(1, $user->getName()->getValue())
            ->setParameter(2, $user->getDocument()->getNumber())
            ->setParameter(3, $user->getDocument()->getType()->getValue())
            ->setParameter(4, $user->getEmail()->getValue())
            ->setParameter(5, md5($user->getPassword()->getValue()))
            ->execute();
    }

    /**
     * @param  Document $document
     * @param  Email    $email
     * @return User|null
     */
    public function findOneByDocumentOrEmail(Document $document, Email $email): ?User
    {
        $data = $this->getConnection()->createQueryBuilder()
            ->select(
                [
                'u.id id',
                'u.name name',
                'u.document_number document_number',
                'u.document_type document_type',
                'u.email email',
                'u.password password'
                ]
            )
            ->from('user', 'u')
            ->where('u.document_number = ? or u.email = ?')
            ->setParameter(0, $document->getNumber())
            ->setParameter(1, $email->getValue())
            ->execute()
            ->fetch();

        if (!$data) {
            return null;
        }

        return $this->instanceUserFromRecord($data);
    }

    /**
     * @param  $data
     * @return User
     */
    private function instanceUserFromRecord(array $data): User
    {
        return new User(
            new Identifier($data['id']),
            new FullName($data['name']),
            new Document(
                $data['document_number'],
                new DocumentType($data['document_type'])
            ),
            new Email($data['email']),
            new Password($data['password'])
        );
    }

    /**
     * @param  Identifier $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findOneById(Identifier $id): User
    {
        $data = $this->getConnection()->createQueryBuilder()
            ->select(
                [
                'u.id id',
                'u.name name',
                'u.document_number document_number',
                'u.document_type document_type',
                'u.email email',
                'u.password password'
                ]
            )
            ->from('user', 'u')
            ->where('u.id = ?')
            ->setParameter(0, $id->getValue())
            ->execute()
            ->fetch();

        if (!$data) {
            throw new UserNotFoundException();
        }

        return $this->instanceUserFromRecord($data);
    }
}
