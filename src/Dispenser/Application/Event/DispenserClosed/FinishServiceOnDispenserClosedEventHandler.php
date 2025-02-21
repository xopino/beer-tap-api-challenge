<?php

namespace App\Dispenser\Application\Event\DispenserClosed;

use App\Dispenser\Domain\Bus\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use App\Dispenser\Domain\Service\MoneySpentCalculator;
use App\Shared\Domain\Bus\Event\EventHandlerInterface;

class FinishServiceOnDispenserClosedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly ServiceRepositoryInterface   $serviceRepository,
        private readonly DispenserRepositoryInterface $dispenserRepository,
        private readonly MoneySpentCalculator         $calculator
    )
    {
    }

    public function __invoke(DispenserClosedDomainEvent $event): void
    {
        try {
            $service = $this->serviceRepository->findOpenServiceByDispenserId($event->aggregateId());

            if (!$service) {
                //TODO: Use DomainException
                throw new \Exception('Service not found');
            }

            $dispenser = $this->dispenserRepository->findById($event->aggregateId());

            if (!$dispenser) {
                throw new \Exception('Dispenser not found');
            }

            $service->finish($dispenser, $this->calculator);

            $this->serviceRepository->save($service);
        } catch (\Throwable $throwable) {
            //TODO: Log Error
            throw $throwable;
        }
    }
}
