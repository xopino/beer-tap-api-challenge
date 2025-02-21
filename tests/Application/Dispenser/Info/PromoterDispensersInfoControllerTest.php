<?php

namespace App\Tests\Application\Dispenser\Info;

use App\DataFixtures\ApplicationDataFixtures;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PromoterDispensersInfoControllerTest extends WebTestCase
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

    public function testGetPromoterDispensersInfoReturnsOk(): void
    {
        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $this->user->getApiToken());

        $this->client->request('GET', '/api/dispenser/info');

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }
}
