<?php

namespace App\Shared\Infrastructure\Bus\Event;

use App\Shared\Domain\Event\DomainEvent;
use App\Shared\Domain\Event\EventBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class SymfonyMessengerEventBus implements EventBusInterface
{
    public function __construct(
        private MessageBusInterface $eventBus
    )
    {
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $envelope = new Envelope($event);
            $this->eventBus->dispatch($envelope);
        }
    }
}
