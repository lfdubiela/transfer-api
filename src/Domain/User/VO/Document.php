<?php
declare(strict_types=1);

namespace App\Domain\User\VO;

use App\Domain\Common\JsonSerialize;
use App\Domain\DomainException\InvalidVO;
use JsonSerializable;

class Document implements JsonSerializable
{
    use JsonSerialize;

    private string $number;

    private DocumentType $type;

    /**
     * Document constructor.
     *
     * @param string       $number
     * @param DocumentType $type
     */
    public function __construct(string $number, DocumentType $type)
    {
        if (strlen($number) > 18) {
            throw new InvalidVO(self::class, $number);
        }

        if ($type->equals(DocumentType::CNPJ())) {
            $this->validateCNPJ($number);
        }

        if ($type->equals(DocumentType::CPF())) {
            $this->validateCPF($number);
        }

        $this->number = $number;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return DocumentType
     */
    public function getType(): DocumentType
    {
        return $this->type;
    }

    /**
     * @param string $number
     */
    private function validateCNPJ(string $number): void
    {
        if (!preg_match('/^\d{2}\.\d{3}\.\d{3}\/\d{4}\-\d{2}$/', $number)) {
            throw new InvalidVO(self::class, $number);
        }
    }

    /**
     * @param string $number
     */
    private function validateCPF(string $number): void
    {
        if (!preg_match('/^\d{3}.?\d{3}.?\d{3}-?\d{2}$/', $number)) {
            throw new InvalidVO(self::class, $number);
        }
    }
}
