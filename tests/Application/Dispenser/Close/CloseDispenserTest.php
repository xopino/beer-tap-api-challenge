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
            'dispenser_id' => 'ff6033ee-7bf3-4f10-952d-2a2b55a94001',
        ];

        $this->client->request('POST', '/api/dispenser/close', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($payload));

        $this->assertResponseIsSuccessful();
    }
}
