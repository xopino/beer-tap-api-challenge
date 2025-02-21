<?php

namespace App\Dispenser\Application\Event\DispenserClosed;

use App\Dispenser\Domain\Bus\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventHandlerInterface;

class FinishServiceOnDispenserClosedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository
    )
    {
    }

    public function __invoke(DispenserClosedDomainEvent $event): void
    {
        try {
            $service = $this->serviceRepository->findByDispenserId($event->aggregateId());

            $service->finish();

            $this->serviceRepository->save($service);
        } catch (\Throwable $throwable) {
            //TODO: Log Error
            throw $throwable;
        }
    }
}
