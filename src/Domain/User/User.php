<?php
declare(strict_types=1);

namespace App\Domain\User;

use App\Domain\Common\JsonSerialize;
use App\Domain\User\VO\Document;
use App\Domain\User\VO\DocumentType;
use App\Domain\User\VO\Email;
use App\Domain\User\VO\FullName;
use App\Domain\Common\VO\Identifier;
use App\Domain\User\VO\Password;
use JsonSerializable;

class User implements JsonSerializable
{
    use JsonSerialize;

    private Identifier $id;

    private FullName $name;

    private Document $document;

    private Email $email;

    private Password $password;

    /**
     * User constructor.
     *
     * @param Identifier $id
     * @param FullName   $name
     * @param Document   $document
     * @param Email      $email
     * @param Password   $password
     */
    public function __construct(
        Identifier $id,
        FullName $name,
        Document $document,
        Email $email,
        Password $password
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->document = $document;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * @return Identifier
     */
    public function getId(): Identifier
    {
        return $this->id;
    }

    /**
     * @return FullName
     */
    public function getName(): FullName
    {
        return $this->name;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * @return Password
     */
    public function getPassword(): Password
    {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function isStore(): bool
    {
        return $this->getDocument()->getType() === DocumentType::CNPJ();
    }
}
