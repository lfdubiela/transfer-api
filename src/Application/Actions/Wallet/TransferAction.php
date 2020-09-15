<?php
declare(strict_types=1);

namespace App\Application\Actions\Wallet;

use App\Domain\Common\VO\Identifier;
use App\Domain\Wallet\VO\Money;
use App\Domain\Wallet\VO\Transfer;
use Psr\Http\Message\ResponseInterface as Response;

class TransferAction extends WalletAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $requestBody = $this->request->getParsedBody();

        $this->logger->info("transfer", $requestBody);

        $transfer = $this->createTransferFromRequest();

        $this->service->transfer($transfer);

        return $this->respondNoContent();
    }

    private function createTransferFromRequest(): Transfer
    {
        $data = $this->request->getParsedBody();

        return new Transfer(
            new Identifier($data['payer']),
            new Identifier($data['payee']),
            new Money($data['value'])
        );
    }
}
