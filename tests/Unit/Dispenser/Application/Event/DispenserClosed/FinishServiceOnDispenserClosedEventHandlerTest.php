<?php

namespace App\Tests\Unit\Dispenser\Application\Event\DispenserClosed;

use App\Dispenser\Application\Event\DispenserClosed\FinishServiceOnDispenserClosedEventHandler;
use App\Dispenser\Domain\Bus\Event\DispenserClosedDomainEvent;
use App\Dispenser\Domain\Entity\Service;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use App\Dispenser\Domain\Service\MoneySpentCalculator;
use App\Tests\Unit\Dispenser\Domain\Entity\DispenserMother;
use App\Tests\Unit\Dispenser\Domain\Entity\ServiceMother;
use PHPUnit\Framework\TestCase;

class FinishServiceOnDispenserClosedEventHandlerTest extends TestCase
{
    private FinishServiceOnDispenserClosedEventHandler $classUnderTest;
    private ServiceRepositoryInterface                 $serviceRepositoryMock;
    private DispenserRepositoryInterface               $dispenserRepositoryMock;
    private MoneySpentCalculator                       $calculatorMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->serviceRepositoryMock   = $this->createMock(ServiceRepositoryInterface::class);
        $this->dispenserRepositoryMock = $this->createMock(DispenserRepositoryInterface::class);
        $this->calculatorMock          = $this->createMock(MoneySpentCalculator::class);

        $this->classUnderTest = new FinishServiceOnDispenserClosedEventHandler(
            $this->serviceRepositoryMock,
            $this->dispenserRepositoryMock,
            $this->calculatorMock
        );
    }

    public function testInvokeShouldFinishService(): void
    {
        $service = ServiceMother::random();
        $dispenser = DispenserMother::random();

        $this->serviceRepositoryMock
            ->expects($this->once())
            ->method('findOpenServiceByDispenserId')
            ->willReturn($service);

        $this->serviceRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with(
                $this->callback(function (Service $service) {
                    return $service->getEndDate() !== null;
                })
            );

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('findById')
            ->willReturn($dispenser);

        $this->calculatorMock
            ->expects($this->once())
            ->method('calculate')
            ->willReturn(3.0);

        $this->classUnderTest->__invoke(
            new DispenserClosedDomainEvent(
                'anUuid'
            )
        );
    }
}
