<?php

namespace App\Tests\Application\Dispenser\Spending;

use App\DataFixtures\ApplicationDataFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DispenserSpendingControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testSuccessfulSpendingQueryReturnsOk(): void
    {
        $validDispenserId = ApplicationDataFixtures::DISPENSER_ID_CLOSED;

        $this->client->request('GET', '/dispenser/' . $validDispenserId . '/spending');

        $this->assertResponseIsSuccessful();

        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('amount', $responseData);
        $this->assertArrayHasKey('usages', $responseData);
    }

    public function testDispenserDoesNotExistReturnsNotFound(): void
    {
        $nonExistentId = 'a8e743a6-75d2-4b21-aa61-655b85bf9c7a';

        $this->client->request('GET', '/dispenser/' . $nonExistentId . '/spending');

        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testBadRequestReturnKo(): void
    {
        $nonExistentId = 'not-an-uuid';

        $this->client->request('GET', '/dispenser/' . $nonExistentId . '/spending');

        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
