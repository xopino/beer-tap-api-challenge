<?php

namespace App\Tests\Application\Dispenser\Create;

use App\DataFixtures\ApplicationDataFixtures;
use App\Dispenser\Domain\Entity\Dispenser;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CreateDispenserTest extends WebTestCase
{
    private KernelBrowser $client;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client        = static::createClient();
        $entityManager = $this->getContainer()->get('doctrine');
        $this->user      = $entityManager->getRepository(User::class)->findOneBy(['email' => ApplicationDataFixtures::USER_EMAIL_OPENED]);
    }

    public function testCreateDispenserReturnsDispenserId(): void
    {
        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $this->user->getApiToken());

        $payload = [
            'flow_volume' => 100,
            'price' => 19.99,
        ];

        $this->client->request('POST', '/api/dispenser/create', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($payload));


        $this->assertResponseIsSuccessful();
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData, 'Response is not valid JSON');
        $this->assertArrayHasKey('dispenser_id', $responseData, 'Response does not have "id" key');
        $this->assertNotEmpty($responseData['dispenser_id'], 'Dispenser id is empty');
    }

    public function testBadRequestWhenMissingRequiredFields(): void
    {
        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $this->user->getApiToken());

        $payload = [
            'flow_volume' => 100
        ];

        $this->client->request(
            'POST',
            '/api/dispenser/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $responseData = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertIsArray($responseData, 'Response is not valid JSON');
        $this->assertArrayHasKey('error', $responseData, 'Response does not contain "error" key');
        $this->assertEquals('Missing required fields', $responseData['error']);
    }

    public function testUnauthorizedWhenNoToken(): void
    {
        $payload = [
            'flow_volume' => 100,
            'price' => 19.99,
        ];

        $this->client->request(
            'POST',
            '/api/dispenser/create',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
