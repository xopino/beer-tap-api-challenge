<?php

namespace App\Dispenser\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

class DispenserClosedDomainEvent extends DomainEvent
{
    public function __construct(
        public readonly string $aggregateId,
        public readonly float  $flowVolume,
        public readonly string $closedAt
    )
    {
        parent::__construct($aggregateId);
    }

    public static function eventName(): string
    {
        return 'dispenser.closed';
    }
}
