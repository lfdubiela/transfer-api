<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Services;

use App\Domain\Wallet\VO\Transfer;
use App\Infrastructure\Http\Services\Exceptions\HttpServiceException;
use App\Infrastructure\Http\Services\Exceptions\InvalidResponseException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientExceptionInterface;
use Exception;

class TransferNotifierService extends Service
{
    const URI = "https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04";

    const APP_KEY = "123456";

    /**
     * @param Transfer $transfer
     */
    public function notify(Transfer $transfer): void
    {
        $request = new Request(
            'POST',
            self::URI,
            [
                 'Content-Type' => 'application-json',
                 'Authorization' => $this->generateAuthorization()
             ],
            $this->createBodySerialized($transfer)
        );

        try {
            $body = $this->getClient()->sendRequest($request)->getBody()->getContents();
            $this->checkResponse($body);
        } catch (ClientExceptionInterface | HttpServiceException $exception) {
            $this->logResponseFailed($exception);
            /**
             * enfileirar mensagem, esse cara nem deveria estar aqui no fluxo de transferia
             * em uma arquitetura eventual, ao finalizar transferia o microservico deveria enviar um evento de
             * transferia realizada, um componente de serviço de notificacao escutaria esse evento exception faria a parte de notificacao,
             * regras de retry fallback exception afins deveriam estar nele;
             */
        }
    }

    /**
     * @param  string $body
     * @throws InvalidResponseException
     */
    private function checkResponse(string $body): void
    {
        $response = json_decode($body, true);

        if (!isset($response['message'])
            || $response['message'] != "Enviado"
        ) {
            throw new InvalidResponseException();
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
        $value = $transfer->getValue()->getValue();
        $payer = $transfer->getPayer()->getValue();

        return json_encode(
            [
            "notification" => "Você recebeu $value do $payer",
            "type" => "balance-transfer"
            ]
        );
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
