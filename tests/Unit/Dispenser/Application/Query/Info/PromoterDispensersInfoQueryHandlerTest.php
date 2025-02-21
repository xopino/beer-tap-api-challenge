<?php

namespace App\Tests\Unit\Dispenser\Application\Query\Info;

use App\Dispenser\Application\Query\Info\PromoterDispensersInfoQueryHandler;
use App\Dispenser\Application\Query\Info\PromoterDispensersInfoQueryResponse;
use App\Dispenser\Domain\Bus\Query\Info\PromoterDispensersInfoQuery;
use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Entity\Service;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use App\Dispenser\Domain\Service\MoneySpentCalculator;
use PHPUnit\Framework\TestCase;

class PromoterDispensersInfoQueryHandlerTest extends TestCase
{
    private DispenserRepositoryInterface $dispenserRepository;
    private ServiceRepositoryInterface $serviceRepository;
    private MoneySpentCalculator $calculator;
    private PromoterDispensersInfoQueryHandler $handler;

    public function setUp(): void
    {
        parent::setUp();

        $this->dispenserRepository = $this->createMock(DispenserRepositoryInterface::class);
        $this->serviceRepository   = $this->createMock(ServiceRepositoryInterface::class);
        $this->calculator          = $this->createMock(MoneySpentCalculator::class);

        $this->handler = new PromoterDispensersInfoQueryHandler(
            $this->dispenserRepository,
            $this->serviceRepository,
            $this->calculator
        );
    }

    public function testHandleQuery(): void
    {
        $promoterId = 1;
        $dispenser = (new Dispenser())->setFlowVolume(2.0)->setPrice(1.5);

        $service1 = Service::create(101, $dispenser->getId())->setStartDate('2024-02-21 12:00:00')->setEndDate('2024-02-21 12:10:00');

        $calculatorMock = $this->createMock(MoneySpentCalculator::class);
        $calculatorMock
            ->method('calculate')
            ->willReturn(3.0);

        $service1->finish($dispenser, $calculatorMock);

        $service2 = Service::create(102, $dispenser->getId())->setStartDate('2024-02-21 12:05:00'); // Ongoing service

        $this->dispenserRepository->expects($this->once())
            ->method('searchByPromoterId')
            ->with($promoterId)
            ->willReturn([$dispenser]);

        $this->serviceRepository->expects($this->once())
            ->method('searchByDispenserId')
            ->with($dispenser->getId())
            ->willReturn([$service1, $service2]);

        $this->calculator->expects($this->exactly(1)) // Only for the ongoing service
        ->method('calculate')
            ->willReturn(15.0);

        $query    = new PromoterDispensersInfoQuery($promoterId);
        $response = $this->handler->__invoke($query);

        $this->assertInstanceOf(PromoterDispensersInfoQueryResponse::class, $response);
        $this->assertEquals(2, $response->totalOfServices);
        $this->assertGreaterThan(0, $response->totalSecondsElapsed);
        $this->assertGreaterThan(0, $response->totalMoneySpent);
    }
}
