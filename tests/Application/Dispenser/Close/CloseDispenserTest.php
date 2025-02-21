<?php

namespace App\Tests\Application\Dispenser\Close;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CloseDispenserTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testCloseDispenserReturnsOk(): void
    {
        $token = '7be58f2b02f7c182e8cab4b915ff25d2f42e3dc1bdd9772d727a4888edca5dd7';

        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $token);

        $payload = [
            'dispenser_id' => '70733e0f-8fa8-4f45-b1f3-661d5d7baf0d',
        ];

        $this->client->request('POST', '/api/dispenser/close', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($payload));

        $this->assertResponseIsSuccessful();
    }
}
