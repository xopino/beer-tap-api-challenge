<?php

namespace App\Dispenser\Application\Command\ChangeStatus;

use App\Dispenser\Domain\Exception\DispenserAlreadyClosedException;
use App\Dispenser\Domain\Exception\DispenserAlreadyOpenException;
use App\Dispenser\Domain\Exception\DispenserDoesNotExist;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Shared\Domain\Command\CommandHandlerInterface;
use App\Shared\Domain\Event\EventBusInterface;

class ChangeStatusCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DispenserRepositoryInterface $dispenserRepository,
        private readonly EventBusInterface            $eventBus
    )
    {
    }

    /**
     * @throws DispenserAlreadyClosedException
     * @throws DispenserAlreadyOpenException
     */
    public function __invoke(ChangeStatusCommand $command): void
    {
        $dispenser = $this->dispenserRepository->findById($command->dispenserId);

        if (!$dispenser) {
            throw new DispenserDoesNotExist();
        }

        $dispenser->changeStatus($command->status, $command->updatedAt);

        $this->dispenserRepository->save($dispenser);
        $this->eventBus->publish(... $dispenser->pullDomainEvents());
    }
}
