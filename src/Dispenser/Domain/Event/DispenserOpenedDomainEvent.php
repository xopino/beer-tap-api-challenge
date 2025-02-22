<?php

namespace App\Dispenser\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

class DispenserOpenedDomainEvent extends DomainEvent
{
    public function __construct(
        public readonly string $aggregateId,
        public readonly float  $flowVolume,
        public readonly string $openedAt
    )
    {
        parent::__construct($aggregateId);
    }

    public static function eventName(): string
    {
        return 'dispenser.opened';
    }
}
