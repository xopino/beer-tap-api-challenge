<?php

namespace App\Tests\Unit\Dispenser\Application\Event\DispenserOpened;

use App\Dispenser\Application\Event\DispenserOpened\CreateDispenserSpendingLineOnDispenserOpenedEventHandler;
use App\Dispenser\Domain\Event\DispenserOpenedDomainEvent;
use App\Dispenser\Domain\Persistence\Repository\DispenserSpendingLineRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateDispenserSpendingLineOnDispenserOpenedEventHandlerTest extends TestCase
{
    private DispenserSpendingLineRepositoryInterface                 $repositoryMock;
    private CreateDispenserSpendingLineOnDispenserOpenedEventHandler $handler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = $this->createMock(DispenserSpendingLineRepositoryInterface::class);
        $this->handler        = new CreateDispenserSpendingLineOnDispenserOpenedEventHandler(
            $this->repositoryMock
        );
    }

    public function testInvokeSavesNewSpendingLine(): void
    {
        $aggregateId = 'dummy-uuid';
        $flowVolume  = 100.0;
        $openedAt    = '2022-01-01T02:00:00Z';

        $event = new DispenserOpenedDomainEvent($aggregateId, $flowVolume, $openedAt);

        $this->repositoryMock
            ->expects($this->once())
            ->method('save');

        $this->handler->__invoke($event);
    }
}
