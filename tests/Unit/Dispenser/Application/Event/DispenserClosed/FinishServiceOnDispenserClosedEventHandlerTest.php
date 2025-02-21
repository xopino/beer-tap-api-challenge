<?php

namespace App\Tests\Unit\Dispenser\Application\Event\DispenserClosed;

use App\Dispenser\Application\Event\DispenserClosed\FinishServiceOnDispenserClosedEventHandler;
use App\Dispenser\Domain\Bus\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Entity\Service;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use App\Tests\Unit\Dispenser\Domain\Entity\ServiceMother;
use PHPUnit\Framework\TestCase;

class FinishServiceOnDispenserClosedEventHandlerTest extends TestCase
{
    private FinishServiceOnDispenserClosedEventHandler $classUnderTest;
    private ServiceRepositoryInterface                 $serviceRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->serviceRepositoryMock = $this->createMock(ServiceRepositoryInterface::class);

        $this->classUnderTest = new FinishServiceOnDispenserClosedEventHandler(
            $this->serviceRepositoryMock
        );
    }

    public function testInvokeShouldFinishService(): void
    {
        $service = ServiceMother::random();

        $this->serviceRepositoryMock
            ->expects($this->once())
            ->method('findByDispenserId')
            ->willReturn($service);

        $this->serviceRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Service $service) {
                return $service->getEndDate() !== null;
            }));

        $this->classUnderTest->__invoke(
            new DispenserClosedDomainEvent(
                'anUuid'
            )
        );
    }
}
