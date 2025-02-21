<?php

namespace App\Dispenser\Domain\Bus\Event;

use App\Shared\Domain\Bus\Event\DomainEvent;

class DispenserClosedDomainEvent extends DomainEvent
{
    public static function eventName(): string
    {
        return 'dispenser.closed';
    }
}
