<?php

namespace App\Dispenser\Domain\Bus\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

class DispenserOpenedDomainEvent extends DomainEvent
{
    public function __construct(
        private readonly string $dispenserId,
        public readonly int     $attendeeId
    )
    {
        parent::__construct($this->dispenserId);
    }

    public static function eventName(): string
    {
        return 'dispenser.opened';
    }
}
