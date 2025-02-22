<?php

namespace App\Shared\Domain\Entity;

use App\Shared\Domain\Event\DomainEvent;

class AggregateRoot
{
    private array $domainEvents = [];

    /**
     * @return DomainEvent[]
     */
    public function pullDomainEvents(): array
    {
        $domainEvents       = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    protected function record(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }
}
