<?php

namespace App\Tests\Unit\Dispenser\Application\Event\DispenserClosed;

use App\Dispenser\Application\Event\DispenserClosed\FinishDispenserSpendingLineOnDispenserClosedEventHandler;
use App\Dispenser\Domain\Entity\DispenserSpendingLine;
use App\Dispenser\Domain\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Persistence\Repository\DispenserSpendingLineRepositoryInterface;
use App\Dispenser\Domain\Service\MoneySpentCalculator;
use PHPUnit\Framework\TestCase;

class FinishDispenserSpendingLineOnDispenserClosedEventHandlerTest extends TestCase
{
    private DispenserSpendingLineRepositoryInterface $repositoryMock;
    private MoneySpentCalculator $calculatorMock;
    private FinishDispenserSpendingLineOnDispenserClosedEventHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = $this->createMock(DispenserSpendingLineRepositoryInterface::class);
        $this->calculatorMock = $this->createMock(MoneySpentCalculator::class);
        $this->handler = new FinishDispenserSpendingLineOnDispenserClosedEventHandler(
            $this->repositoryMock,
            $this->calculatorMock
        );
    }

    public function testInvokeFinishesSpendingLineWhenOpenSpendingLineFound(): void
    {
        $aggregateId = 'dummy-id';
        $flowVolume  = 10.0;
        $updatedAt   = '2022-01-01T02:00:00Z';

        $spendingLine = DispenserSpendingLine::create(
            $aggregateId,
            $flowVolume,
            $updatedAt
        );

        $event = new DispenserClosedDomainEvent($aggregateId, $flowVolume, $updatedAt);

        $this->repositoryMock->expects($this->once())
            ->method('findOpenDispenserSpendingLine')
            ->with($aggregateId)
            ->willReturn($spendingLine);

        $this->calculatorMock
            ->expects($this->once())
            ->method('calculate');

        $this->repositoryMock->expects($this->once())
            ->method('save')
            ->with($spendingLine);

        $this->handler->__invoke($event);
    }

    public function testInvokeDoesNothingWhenNoOpenSpendingLineFound(): void
    {
        $aggregateId = 'dummy-id';
        $flowVolume  = 10.0;
        $updatedAt   = '2022-01-01T02:00:00Z';

        $event = new DispenserClosedDomainEvent($aggregateId, $flowVolume, $updatedAt);

        $this->repositoryMock->expects($this->once())
            ->method('findOpenDispenserSpendingLine')
            ->with($aggregateId)
            ->willReturn(null);

        $this->repositoryMock
            ->expects($this->never())
            ->method('save');

        $this->handler->__invoke($event);
    }
}
