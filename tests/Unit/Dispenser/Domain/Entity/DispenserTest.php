<?php

namespace App\Tests\Unit\Dispenser\Domain\Entity;

use App\Dispenser\Domain\Bus\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Bus\Event\DispenserOpenedDomainEvent;
use App\Dispenser\Domain\Entity\Dispenser;
use PHPUnit\Framework\TestCase;

class DispenserTest extends TestCase
{
    public function testOpenRecordsDomainEvent(): void
    {
        $dispenser = $this->getMockBuilder(Dispenser::class)
            ->onlyMethods(['record'])
            ->getMock();
        $attendeeId = 123;

        $dispenser->expects($this->once())
            ->method('record')
            ->with($this->isInstanceOf(DispenserOpenedDomainEvent::class));

        $dispenser->open($attendeeId);

        $this->assertEquals(Dispenser::STATUS_OPEN, $dispenser->getStatus());
    }

    public function testCloseRecordsDomainEvent(): void
    {
        $dispenser = $this->getMockBuilder(Dispenser::class)
            ->onlyMethods(['record'])
            ->getMock();

        $dispenser->open(123);

        $dispenser->expects($this->once())
            ->method('record')
            ->with($this->isInstanceOf(DispenserClosedDomainEvent::class));

        $dispenser->close();

        $this->assertEquals(Dispenser::STATUS_CLOSED, $dispenser->getStatus());
    }

    public function testOpenThrowsExceptionIfAlreadyOpen(): void
    {
        $dispenser = new Dispenser();
        $attendeeId = 123;

        $dispenser->open($attendeeId);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Dispenser already open');

        $dispenser->open($attendeeId);
    }
}
