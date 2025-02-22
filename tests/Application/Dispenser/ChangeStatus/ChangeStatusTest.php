<?php

namespace App\Tests\Application\Dispenser\ChangeStatus;

use App\DataFixtures\ApplicationDataFixtures;
use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ChangeStatusTest extends WebTestCase
{
    private KernelBrowser $client;

    private Dispenser $dispenserOpened;
    private Dispenser $dispenserClosed;

    private DispenserRepositoryInterface $dispenserRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client              = static::createClient();
        $entityManager             = $this->getContainer()->get('doctrine');
        $this->dispenserRepository = $entityManager->getRepository(Dispenser::class);
        $this->dispenserOpened     = $this->dispenserRepository->findById(ApplicationDataFixtures::DISPENSER_ID_OPENED);
        $this->dispenserClosed     = $this->dispenserRepository->findById(ApplicationDataFixtures::DISPENSER_ID_CLOSED);

    }

    public function tearDown(): void
    {
        parent::tearDown();
        $dispenserOpened = $this->dispenserRepository->findById($this->dispenserOpened->getId());
        $dispenserClosed = $this->dispenserRepository->findById($this->dispenserClosed->getId());

        if (!$dispenserOpened->isOpen()) {
            $dispenserOpened->changeStatus(Dispenser::STATUS_OPEN, date('Y-m-d\TH:i:s\Z'));
            $this->dispenserRepository->save($dispenserOpened);
        }

        if ($dispenserClosed->isOpen()) {
            $dispenserClosed->changeStatus(Dispenser::STATUS_CLOSED, date('Y-m-d\TH:i:s\Z'));
            $this->dispenserRepository->save($dispenserClosed);
        }
    }

    public function testChangeStatusFromClosedToOpenShouldReturnOk(): void
    {
        $payload = [
            'status'     => 'open',
            'updated_at' => '2022-01-01T02:00:00Z'
        ];

        $this->client->request('PUT', '/dispenser/' . ApplicationDataFixtures::DISPENSER_ID_CLOSED . '/status', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ],                     json_encode($payload));

        $this->assertResponseIsSuccessful();
    }

    public function testChangeStatusFromOpenToClosedShouldReturnOk(): void
    {
        $payload = [
            'status'     => 'close',
            'updated_at' => date('Y-m-d\TH:i:s\Z')
        ];

        $this->client->request('PUT', '/dispenser/' . ApplicationDataFixtures::DISPENSER_ID_OPENED . '/status', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ],                     json_encode($payload));

        $this->assertResponseIsSuccessful();
    }

    public function testMissingParametersShouldReturnInternalServerError(): void
    {
        $payload = [
            'status' => 'open'
        ];

        $this->client->request('PUT', '/dispenser/' . ApplicationDataFixtures::DISPENSER_ID_CLOSED . '/status', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($payload));

        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testChangeStatusToOpenWhenAlreadyOpenShouldReturnConflict(): void
    {
        $payload = [
            'status'     => 'open',
            'updated_at' => '2022-01-01T02:00:00Z'
        ];

        $this->client->request('PUT', '/dispenser/' . ApplicationDataFixtures::DISPENSER_ID_OPENED . '/status', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($payload));

        $this->assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
    }

    public function testChangeStatusToClosedWhenAlreadyClosedShouldReturnConflict(): void
    {
        $payload = [
            'status'     => 'close',
            'updated_at' => '2022-01-01T02:00:00Z'
        ];

        $this->client->request('PUT', '/dispenser/' . ApplicationDataFixtures::DISPENSER_ID_CLOSED . '/status', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode($payload));

        $this->assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
    }
}
