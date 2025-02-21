<?php

namespace App\Tests\Application\Dispenser\Info;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PromoterDispensersInfoControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testGetPromoterDispensersInfoReturnsOk(): void
    {
        $token = '7be58f2b02f7c182e8cab4b915ff25d2f42e3dc1bdd9772d727a4888edca5dd7';

        $this->client->setServerParameter('HTTP_AUTHORIZATION', 'Bearer ' . $token);

        $this->client->request('GET', '/api/dispenser/info');

        $this->assertResponseIsSuccessful();
        $this->assertJson($this->client->getResponse()->getContent());
    }
}
