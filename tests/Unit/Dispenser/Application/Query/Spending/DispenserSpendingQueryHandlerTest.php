<?php

namespace App\Tests\Unit\Dispenser\Application\Query\Spending;

use App\Dispenser\Application\Query\Spending\DispenserSpendingQuery;
use App\Dispenser\Application\Query\Spending\DispenserSpendingQueryHandler;
use App\Dispenser\Application\Query\Spending\DispenserSpendingQueryResponse;
use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Entity\DispenserSpendingLine;
use App\Dispenser\Domain\Exception\DispenserDoesNotExist;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Persistence\Repository\DispenserSpendingLineRepositoryInterface;
use App\Dispenser\Domain\Service\MoneySpentCalculator;
use PHPUnit\Framework\TestCase;

class DispenserSpendingQueryHandlerTest extends TestCase
{
    private DispenserRepositoryInterface $dispenserRepositoryMock;
    private DispenserSpendingLineRepositoryInterface $spendingLineRepositoryMock;
    private MoneySpentCalculator $calculatorMock;
    private DispenserSpendingQueryHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dispenserRepositoryMock = $this->createMock(DispenserRepositoryInterface::class);
        $this->spendingLineRepositoryMock = $this->createMock(DispenserSpendingLineRepositoryInterface::class);
        $this->calculatorMock = $this->createMock(MoneySpentCalculator::class);

        $this->handler = new DispenserSpendingQueryHandler(
            $this->dispenserRepositoryMock,
            $this->spendingLineRepositoryMock,
            $this->calculatorMock
        );
    }

    public function testInvokeReturnsExpectedResponse(): void
    {
        $dispenserId = 'dummy-uuid';
        $flowVolume = 0.064;

        $dispenserStub = $this->createMock(Dispenser::class);
        $dispenserStub->method('getId')
            ->willReturn($dispenserId);
        $dispenserStub->method('getFlowVolume')
            ->willReturn($flowVolume);

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($dispenserId)
            ->willReturn($dispenserStub);

        $spendingLine1 = $this->getMockBuilder(DispenserSpendingLine::class)
            ->disableOriginalConstructor()
            ->getMock();
        $spendingLine1->method('getOpenedAt')
            ->willReturn('2022-01-01T13:50:58Z');
        $spendingLine1->method('getClosedAt')
            ->willReturn('2022-01-01T14:00:00Z');
        $spendingLine1->method('getFlowVolume')
            ->willReturn($flowVolume);
        $spendingLine1->method('getTotalSpent')
            ->willReturn(1.23);

        $spendingLine2 = $this->getMockBuilder(DispenserSpendingLine::class)
            ->disableOriginalConstructor()
            ->getMock();
        $spendingLine2->method('getOpenedAt')
            ->willReturn('2022-01-01T15:00:00Z');
        $spendingLine2->method('getClosedAt')
            ->willReturn(null);
        $spendingLine2->method('getFlowVolume')
            ->willReturn($flowVolume);
        $spendingLine2->method('getTotalSpent')
            ->willReturn(null);

        $this->spendingLineRepositoryMock
            ->expects($this->once())
            ->method('searchByDispenserId')
            ->with($dispenserId)
            ->willReturn([$spendingLine1, $spendingLine2]);

        $this->calculatorMock
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(2.34);

        $query = new DispenserSpendingQuery($dispenserId);
        $response = $this->handler->__invoke($query);

        // Expected total amount: 1.23 (from spendingLine1) + 2.34 (from spendingLine2) = 3.57.
        $this->assertEquals(3.57, $response->amount);

        // Expected usages.
        $expectedUsages = [
            [
                'opened_at'   => '2022-01-01T13:50:58Z',
                'closed_at'   => '2022-01-01T14:00:00Z',
                'flow_volume' => $flowVolume,
                'total_spent' => 1.23,
            ],
            [
                'opened_at'   => '2022-01-01T15:00:00Z',
                'closed_at'   => null,
                'flow_volume' => $flowVolume,
                'total_spent' => 2.34,
            ],
        ];

        $this->assertEquals($expectedUsages, $response->usages);
    }

    public function testInvokeThrowsDispenserDoesNotExistException(): void
    {
        $dispenserId = 'non-existent-uuid';

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($dispenserId)
            ->willReturn(null);

        $query = new DispenserSpendingQuery($dispenserId);

        $this->expectException(DispenserDoesNotExist::class);
        $this->handler->__invoke($query);
    }
}
