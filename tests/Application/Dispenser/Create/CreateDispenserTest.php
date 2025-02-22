<?php

namespace App\Tests\Application\Dispenser\Create;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateDispenserTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client        = static::createClient();
    }

    public function testCreateDispenserReturnsDispenserId(): void
    {
        $payload = [
            'flow_volume' => 0.0653,
        ];

        $this->client->request('POST', '/dispenser', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($payload));


        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData, 'Response is not valid JSON');
        $this->assertArrayHasKey('id', $responseData, 'Response does not have "id" key');
        $this->assertArrayHasKey('flow_volume', $responseData, 'Response does not have "flow_volume" key');
        $this->assertNotEmpty($responseData['id'], 'Dispenser id is empty');
    }

    public function testBadRequestWhenMissingRequiredFields(): void
    {
        $payload = [
            'flowVolume' => 100
        ];

        $this->client->request(
            'POST',
            '/dispenser',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData, 'Response is not valid JSON');
        $this->assertArrayHasKey('error', $responseData, 'Response does not contain "error" key');
        $this->assertEquals('Missing flow_volume', $responseData['error']);
    }
}
