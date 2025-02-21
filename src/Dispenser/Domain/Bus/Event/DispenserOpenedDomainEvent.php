<?php

namespace App\Dispenser\Domain\Bus\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

class DispenserOpenedDomainEvent extends DomainEvent
{
    public function __construct(
        public readonly string|int $aggregateId,
        public readonly int        $attendeeId
    )
    {
        $this->occurredOn = new \DateTimeImmutable();
    }

    public static function eventName(): string
    {
        return 'dispenser.opened';
    }
}
