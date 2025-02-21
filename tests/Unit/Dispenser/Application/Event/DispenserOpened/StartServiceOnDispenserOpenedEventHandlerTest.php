<?php

namespace App\Tests\Unit\Dispenser\Application\Event\DispenserOpened;

use App\Dispenser\Application\Event\DispenserOpened\CreateServiceOnDispenserOpenedEventHandler;
use App\Dispenser\Domain\Bus\Event\DispenserOpenedDomainEvent;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use PHPUnit\Framework\TestCase;

class StartServiceOnDispenserOpenedEventHandlerTest extends TestCase
{
    private CreateServiceOnDispenserOpenedEventHandler $classUnderTest;
    private ServiceRepositoryInterface                 $serviceRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->serviceRepositoryMock = $this->createMock(ServiceRepositoryInterface::class);
        $this->classUnderTest        = new CreateServiceOnDispenserOpenedEventHandler(
            $this->serviceRepositoryMock
        );
    }

    public function testInvoke(): void
    {
        $this->serviceRepositoryMock->expects($this->once())
            ->method('save');

        $this->classUnderTest->__invoke(
            new DispenserOpenedDomainEvent(
                'anUuid',
                1
            )
        );
    }

    public function testInvokeShouldThrowExceptionWhenFails(): void
    {
        $this->serviceRepositoryMock->expects($this->once())
            ->method('save')
            ->willThrowException(new \Exception('An error occurred'));

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('An error occurred');

        $this->classUnderTest->__invoke(
            new DispenserOpenedDomainEvent(
                'anUuid',
                1
            )
        );
    }
}
