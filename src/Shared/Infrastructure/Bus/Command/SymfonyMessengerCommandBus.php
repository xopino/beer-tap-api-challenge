<?php

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Domain\Bus\Command\CommandBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyMessengerCommandBus implements CommandBusInterface
{
    public function __construct(
        private MessageBusInterface $commandBus
    )
    {
    }

    public function dispatch(Command $command): void
    {
        $envelope = new Envelope($command);
        $this->commandBus->dispatch($envelope);
    }
}
