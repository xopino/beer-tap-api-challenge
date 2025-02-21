<?php

namespace App\Shared\Domain\Bus\Event;

interface EventBusInterface
{
    public function publish(DomainEvent ...$events): void;
}
