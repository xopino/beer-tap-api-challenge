<?php

namespace App\Dispenser\Application\Event\DispenserClosed;

use App\Dispenser\Domain\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Persistence\Repository\DispenserSpendingLineRepositoryInterface;
use App\Dispenser\Domain\Service\MoneySpentCalculator;
use App\Shared\Domain\Event\EventHandlerInterface;

class FinishDispenserSpendingLineOnDispenserClosedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly DispenserSpendingLineRepositoryInterface $serviceRepository,
        private readonly MoneySpentCalculator                     $calculator
    )
    {
    }

    public function __invoke(DispenserClosedDomainEvent $event): void
    {
        try {
            $dispenserSpendingLine = $this->serviceRepository->findOpenDispenserSpendingLine($event->aggregateId());

            if (!$dispenserSpendingLine) {
                //TODO: Log
                return;
            }

            $dispenserSpendingLine->finish($event->closedAt, $this->calculator);

            $this->serviceRepository->save($dispenserSpendingLine);
        } catch (\Throwable $throwable) {
            //TODO: Log Error
            throw $throwable;
        }
    }
}
