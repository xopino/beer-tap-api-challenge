<?php

namespace App\Dispenser\Application\Event\DispenserOpened;

use App\Dispenser\Domain\Entity\DispenserSpendingLine;
use App\Dispenser\Domain\Event\DispenserOpenedDomainEvent;
use App\Dispenser\Domain\Persistence\Repository\DispenserSpendingLineRepositoryInterface;
use App\Shared\Domain\Event\EventHandlerInterface;

class CreateDispenserSpendingLineOnDispenserOpenedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly DispenserSpendingLineRepositoryInterface $serviceRepository
    )
    {
    }

    public function __invoke(DispenserOpenedDomainEvent $event): void
    {
        try {
            $dispenserSpendingLine = DispenserSpendingLine::create(
                $event->aggregateId(),
                $event->flowVolume,
                $event->openedAt
            );

            $this->serviceRepository->save($dispenserSpendingLine);
        } catch (\Throwable $throwable) {
            //TODO: Log Error
            throw $throwable;
        }
    }
}
