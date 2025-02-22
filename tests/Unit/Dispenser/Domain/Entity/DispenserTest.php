<?php

namespace App\Tests\Unit\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Event\DispenserOpenedDomainEvent;
use App\Dispenser\Domain\Exception\DispenserAlreadyOpenException;
use PHPUnit\Framework\TestCase;

class DispenserTest extends TestCase
{
    public function testOpenRecordsDomainEvent(): void
    {
        $dispenser = DispenserMother::closed('e53f4c1c-423f-47f1-a5a6-ec739bb5f499');
        $dispenser->pullDomainEvents();

        $updatedAt = '2022-01-01T13:50:58Z';

        $dispenser->changeStatus(Dispenser::STATUS_OPEN, $updatedAt);

        $events = $dispenser->pullDomainEvents();

        $this->assertInstanceOf(DispenserOpenedDomainEvent::class, $events[0]);
    }

    public function testCloseRecordsDomainEvent(): void
    {
        $dispenser = DispenserMother::opened('e53f4c1c-423f-47f1-a5a6-ec739bb5f499');
        $dispenser->pullDomainEvents();

        $updatedAt = '2022-01-01T13:50:58Z';

        $dispenser->changeStatus(Dispenser::STATUS_CLOSED, $updatedAt);

        $events = $dispenser->pullDomainEvents();

        $this->assertInstanceOf(DispenserClosedDomainEvent::class, $events[0]);
    }

    public function testOpenThrowsExceptionIfAlreadyOpen(): void
    {
        $dispenser = DispenserMother::opened('e53f4c1c-423f-47f1-a5a6-ec739bb5f499');
        $updatedAt = '2022-01-01T15:00:00Z';

        $this->expectException(DispenserAlreadyOpenException::class);

        $dispenser->changeStatus(Dispenser::STATUS_OPEN, $updatedAt);
    }
}
