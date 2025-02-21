<?php

namespace App\Shared\Domain\Bus\Event;

abstract class DomainEvent
{
    const EVENT_NAME_KEY   = 'event_name';
    const OCCURRED_ON_KEY  = 'occurred_on';
    const AGGREGATE_ID_KEY = 'aggregate_id';

    private readonly \DateTimeImmutable $occurredOn;

    public function __construct(
        private readonly string|int $aggregateId
    )
    {
        $this->occurredOn = new \DateTimeImmutable();
    }

    abstract public static function eventName(): string;

    final public function toPrimitives(): array
    {
        return [
            self::EVENT_NAME_KEY   => static::eventName(),
            self::OCCURRED_ON_KEY  => $this->occurredOn()->format('Y-m-d H:i:s'),
            self::AGGREGATE_ID_KEY => $this->aggregateId
        ];
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
