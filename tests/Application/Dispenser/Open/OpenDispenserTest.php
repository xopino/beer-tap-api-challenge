<?php

namespace App\Tests\Application\Dispenser\Open;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OpenDispenserTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testOpenDispenserReturnsOk(): void
    {
        $token = '30f873849bcaf287a45628c72fc445c85e5c398bc64047bdd4d9e229453ebedb';

        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $token);

        $payload = [
            'dispenser_id' => '70733e0f-8fa8-4f45-b1f3-661d5d7baf0d',
        ];

        $this->client->request('POST', '/api/dispenser/open', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($payload));

        $this->assertResponseIsSuccessful();
    }
}
