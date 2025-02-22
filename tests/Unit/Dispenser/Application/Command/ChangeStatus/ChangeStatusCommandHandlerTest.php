<?php

namespace App\Tests\Unit\Dispenser\Application\Command\ChangeStatus;

use App\Dispenser\Application\Command\ChangeStatus\ChangeStatusCommand;
use App\Dispenser\Application\Command\ChangeStatus\ChangeStatusCommandHandler;
use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Exception\DispenserDoesNotExist;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Shared\Domain\Event\EventBusInterface;
use App\Shared\Domain\Service\TransactionManager\TransactionManagerInterface;
use App\Tests\Unit\Dispenser\Domain\Entity\DispenserMother;
use PHPUnit\Framework\TestCase;

class ChangeStatusCommandHandlerTest extends TestCase
{
    private ChangeStatusCommandHandler $handler;
    private DispenserRepositoryInterface $dispenserRepositoryMock;
    private EventBusInterface $eventBusMock;
    private TransactionManagerInterface $transactionManagerMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->dispenserRepositoryMock = $this->createMock(DispenserRepositoryInterface::class);
        $this->eventBusMock            = $this->createMock(EventBusInterface::class);
        $this->transactionManagerMock  = $this->createMock(TransactionManagerInterface::class);

        $this->handler = new ChangeStatusCommandHandler(
            $this->dispenserRepositoryMock,
            $this->eventBusMock,
            $this->transactionManagerMock
        );
    }

    public function testInvokeShouldChangeStatusFromOpenToClose(): void
    {
        $dispenser = DispenserMother::opened('314d2795-f173-4b0c-8574-fd4d714ce8e9');
        $dispenser->pullDomainEvents();

        $command = new ChangeStatusCommand(
            dispenserId: $dispenser->getId(),
            status: Dispenser::STATUS_CLOSED,
            updatedAt: '2022-01-01T02:00:00Z'
        );

        $this->transactionManagerMock
            ->expects($this->once())
            ->method('beginTransaction');
        $this->transactionManagerMock
            ->expects($this->once())
            ->method('commit');
        $this->transactionManagerMock
            ->expects($this->never())
            ->method('rollback');

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($dispenser->getId())
            ->willReturn($dispenser);

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($dispenser);

        $events = $dispenser->pullDomainEvents();
        $this->eventBusMock
            ->expects($this->once())
            ->method('publish')
            ->with(...$events);

        $this->handler->__invoke($command);

        $this->assertFalse($dispenser->isOpen());
    }

    public function testInvokeShouldChangeStatusFromCloseToOpen(): void
    {
        $dispenser = DispenserMother::closed('314d2795-f173-4b0c-8574-fd4d714ce8e9');
        $dispenser->pullDomainEvents();

        $command = new ChangeStatusCommand(
            dispenserId: $dispenser->getId(),
            status: Dispenser::STATUS_OPEN,
            updatedAt: '2022-01-01T02:00:00Z'
        );

        $this->transactionManagerMock
            ->expects($this->once())
            ->method('beginTransaction');
        $this->transactionManagerMock
            ->expects($this->once())
            ->method('commit');
        $this->transactionManagerMock
            ->expects($this->never())
            ->method('rollback');

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with($dispenser->getId())
            ->willReturn($dispenser);

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($dispenser);

        $events = $dispenser->pullDomainEvents();
        $this->eventBusMock
            ->expects($this->once())
            ->method('publish')
            ->with(...$events);

        $this->handler->__invoke($command);

        $this->assertTrue($dispenser->isOpen());
    }

    public function testInvokeShouldThrowExceptionWhenDispenserNotFound(): void
    {
        $command = new ChangeStatusCommand(
            dispenserId: 'non-existing-id',
            status: Dispenser::STATUS_CLOSED,
            updatedAt: '2022-01-01T02:00:00Z'
        );

        $this->transactionManagerMock
            ->expects($this->once())
            ->method('beginTransaction');
        $this->transactionManagerMock
            ->expects($this->once())
            ->method('rollback');
        $this->transactionManagerMock
            ->expects($this->never())
            ->method('commit');

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->with('non-existing-id')
            ->willReturn(null);

        $this->expectException(DispenserDoesNotExist::class);

        $this->handler->__invoke($command);
    }
}
