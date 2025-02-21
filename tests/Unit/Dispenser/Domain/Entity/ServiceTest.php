<?php

namespace App\Tests\Unit\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Entity\Service;
use App\Dispenser\Domain\Service\MoneySpentCalculator;
use PHPUnit\Framework\TestCase;

class ServiceTest extends TestCase
{
    public function testFinishCalculatesMoneySpentAndSetsEndDate(): void
    {
        $service = Service::create(123, 'dispenser-1');

        $dispenser = $this->createMock(Dispenser::class);
        $dispenser->method('getFlowVolume')->willReturn(2.0);
        $dispenser->method('getPrice')->willReturn(1.5);

        $calculator = $this->createMock(MoneySpentCalculator::class);
        $calculator->expects($this->once())
            ->method('calculate')
            ->with(
                $service->getStartDate(),
                $this->callback(fn($endDate) => strtotime($endDate) >= strtotime($service->getStartDate())),
                2.0,
                1.5
            )
            ->willReturn(15.0);

        $service->finish($dispenser, $calculator);

        $this->assertNotNull($service->getEndDate());
        $this->assertEquals(15.0, $service->getMoneySpent());
    }
}
