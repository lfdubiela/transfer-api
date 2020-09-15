<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Services;

use App\Domain\Wallet\VO\Transfer;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Exception;

class TransferAuthorizerService extends Service
{
    const URI = "https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6";

    const APP_KEY = "1234";

    /**
     * @todo melhorar tratamento de fallback caso de falha.
     *
     * @param  Transfer $transfer
     * @return bool
     */
    public function authorize(Transfer $transfer): bool
    {
        return true;

        $request = new Request(
            'POST',
            self::URI,
            [
                'Content-Type'  => 'application-json',
                'Authorization' => $this->generateAuthorization()
            ],
            $this->createBodySerialized($transfer)
        );

        try {
            $body = $this->getClient()
                ->sendRequest($request)
                ->getBody()
                ->getContents();

            return $this->checkResponse($body);
        } catch (ClientExceptionInterface $e) {
            $this->logResponseFailed($e);
            return false;
        }
    }

    /**
     * @return string
     */
    private function generateAuthorization(): string
    {
        return md5(self::APP_KEY);
    }

    /**
     * @param  Transfer $transfer
     * @return string
     */
    private function createBodySerialized(Transfer $transfer): string
    {
        return json_encode(
            [
            'payer'  => $transfer->getPayer()->getValue(),
            'amount' => $transfer->getValue()->getValue()
            ]
        );
    }

    /**
     * @param  string $body
     * @return bool
     */
    private function checkResponse(string $body): bool
    {
        $response = json_decode($body, true);

        return isset($response['message']) &&
            $response['message'] == "Autorizado";
    }

    /**
     * @param Exception $exception
     */
    private function logResponseFailed(Exception $exception): void
    {
        $this->logger->error(
            'request failed',
            [
            'code'    => $exception->getCode(),
            'message' => $exception->getMessage()
            ]
        );
    }
}
