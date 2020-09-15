<?php
declare(strict_types=1);

namespace Tests\Helpers;

class TransferHelper
{
    public static function getPayload($payer = 1, $payee = 2, $value = 40.00)
    {
        return [
            'payer' => $payer,
            'payee' => $payee,
            'value' => $value
        ];
    }
}