<?php

namespace App\Dispenser\Application\Event\DispenserOpened;

use App\Dispenser\Domain\Bus\Event\DispenserOpenedDomainEvent;
use App\Dispenser\Domain\Entity\Service;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use App\Shared\Domain\Bus\Event\EventHandlerInterface;

class CreateServiceOnDispenserOpenedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository
    )
    {
    }

    public function __invoke(DispenserOpenedDomainEvent $event): void
    {
        try {
            $service = Service::create(
                attendeeId : $event->attendeeId,
                dispenserId: $event->aggregateId(),
            );

            $this->serviceRepository->save($service);
        } catch (\Throwable $throwable) {
            //TODO: Log Error
            throw $throwable;
        }
    }
}
