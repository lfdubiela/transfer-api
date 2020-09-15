<?php


namespace App\Domain\User\VO;

use MyCLabs\Enum\Enum;

/**
 * Class DocumentType
 *
 * @package App\Domain\User\VO
 *
 * @method static self CPF
 * @method static self CNPJ
 */
class DocumentType extends Enum
{
    private const CPF = "CPF", CNPJ = "CNPJ";
}
