<?php

namespace App\Dispenser\Application\Command\CloseDispenser;

use App\Dispenser\Domain\Bus\Command\CloseDispenser\CloseDispenserCommand;
use App\Dispenser\Domain\Bus\Command\OpenDispenser\OpenDispenserCommand;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Shared\Domain\Bus\Command\CommandHandlerInterface;
use App\Shared\Domain\Bus\Event\EventBusInterface;

class CloseDispenserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly DispenserRepositoryInterface $dispenserRepository,
        private readonly EventBusInterface            $eventBus
    )
    {
    }

    public function __invoke(CloseDispenserCommand $command): void
    {
        try {
            $dispenser = $this->dispenserRepository->findById($command->dispenserId);

            if ($dispenser === null) {
                throw new \Exception('Dispenser not found');
            }

            //TODO: Use Transactional outbox pattern
            $dispenser->close();

            $this->dispenserRepository->save($dispenser);
            $this->eventBus->publish(...$dispenser->pullDomainEvents());
        } catch (\Throwable $throwable) {
            //TODO: Use DomainError
            //TODO: Use Logger
            throw new \Exception('Error opening dispenser');
        }
    }

}
