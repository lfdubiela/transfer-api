<?php
declare(strict_types=1);

namespace Tests\Application\Actions\Wallet;

use Tests\TestCase;

class TransferActionTest extends TestCase
{
    use WalletActionTest;

    public function testTransferCreated()
    {
        $this->setUpDependencies();

        $app = $this->getAppInstance();

        $request = $this->createRequest(
            'POST',
            '/transfer',
            ['Content-Type' => 'application/json'],
            [
                'payer' => 2,
                'payee' => 3,
                'value' => 20.00
            ]
        );

        $response = $app->handle($request);

        $payload = (string)$response->getBody();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEmpty($payload);
    }

    public function testUserStoreCannotCreateTransfer()
    {
        $this->setUpDependencies();

        $app = $this->getAppInstance();

        $request = $this->createRequest(
            'POST',
            '/transfer',
            [
                'Content-Type' => 'application/json'
            ],
            [
                'payer' => 1,
                'payee' => 2,
                'value' => 20.00
            ]
        );

        $response = $app->handle($request);

        $payload = (string)$response->getBody();

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(
            json_encode([
                "code" => 422,
                "description" => "Store cannot make transfer!"
            ], JSON_PRETTY_PRINT),
            $payload
        );
    }

    public function testUserWithInsufficientBalance()
    {
        $this->setUpDependencies();

        $app = $this->getAppInstance();

        $request = $this->createRequest(
            'POST',
            '/transfer',
            [
                'Content-Type' => 'application/json'
            ],
            [
                'payer' => 2,
                'payee' => 1,
                'value' => 100.00
            ]
        );

        $response = $app->handle($request);

        $payload = (string)$response->getBody();

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(
            json_encode([
                "code" => 422,
                "description" => "User has insufficient balance to transfer this amount!"
            ], JSON_PRETTY_PRINT),
            $payload
        );
    }

    public function testUnauthorizedTransfer()
    {
        $this->setUpDependencies(['authorize' => false]);

        $app = $this->getAppInstance();

        $request = $this->createRequest(
            'POST',
            '/transfer',
            [
                'Content-Type' => 'application/json'
            ],
            [
                'payer' => 2,
                'payee' => 1,
                'value' => 100.00
            ]
        );

        $response = $app->handle($request);

        $payload = (string)$response->getBody();

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertEquals(
            json_encode([
                "code" => 422,
                "description" => "The transfer was not authorized!"
            ], JSON_PRETTY_PRINT),
            $payload
        );
    }
}
