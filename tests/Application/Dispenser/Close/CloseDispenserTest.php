<?php

namespace App\Tests\Application\Dispenser\Close;

use App\DataFixtures\ApplicationDataFixtures;
use App\Dispenser\Domain\Entity\Dispenser;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CloseDispenserTest extends WebTestCase
{
    private KernelBrowser $client;

    private User $user;

    private Dispenser $dispenser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client        = static::createClient();
        $entityManager = $this->getContainer()->get('doctrine');
        $this->dispenser = $entityManager->getRepository(Dispenser::class)->findById(ApplicationDataFixtures::DISPENSER_ID_OPENED);
        $this->user      = $entityManager->getRepository(User::class)->findOneBy(['email' => ApplicationDataFixtures::USER_EMAIL_OPENED]);
    }

    public function testCloseDispenserReturnsOk(): void
    {
        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $this->user->getApiToken());

        $payload = [
            'dispenser_id' => $this->dispenser->getId(),
        ];

        $this->client->request('POST', '/api/dispenser/close', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ],                     json_encode($payload));

        $this->assertResponseIsSuccessful();
    }
}
