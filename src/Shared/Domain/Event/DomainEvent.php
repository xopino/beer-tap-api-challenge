<?php

namespace App\Shared\Domain\Event;

abstract class DomainEvent
{
    private readonly \DateTimeImmutable $occurredOn;

    public function __construct(
        private readonly string|int $aggregateId
    )
    {
        $this->occurredOn = new \DateTimeImmutable();
    }

    abstract public static function eventName(): string;

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
