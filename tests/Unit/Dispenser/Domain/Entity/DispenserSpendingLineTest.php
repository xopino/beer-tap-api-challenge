<?php

namespace App\Tests\Unit\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Entity\DispenserSpendingLine;
use App\Dispenser\Domain\Service\MoneySpentCalculator;
use PHPUnit\Framework\TestCase;

class DispenserSpendingLineTest extends TestCase
{
    public function testFinishCalculatesMoneySpentAndSetsEndDate(): void
    {
        $openedAt    = '2022-01-01T12:00:00Z';
        $closedAt    = '2022-01-01T12:00:61Z';
        $flowVolume  = 2.0;
        $dispenserId = 'dispenser-1';

        $service = DispenserSpendingLine::create($dispenserId, $flowVolume, $openedAt);

        $calculator = $this->createMock(MoneySpentCalculator::class);
        $calculator->expects($this->once())
            ->method('calculate')
            ->willReturn(15.0);

        $service->finish($closedAt, $calculator);

        $this->assertNotNull($service->getClosedAt());
        $this->assertEquals(15.0, $service->getTotalSpent());
    }
}
