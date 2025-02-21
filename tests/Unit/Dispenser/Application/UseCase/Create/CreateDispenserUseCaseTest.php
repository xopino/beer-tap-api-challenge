<?php

namespace App\Tests\Unit\Dispenser\Application\UseCase\Create;

use App\Dispenser\Application\UseCase\Create\CreateDispenserUseCase;
use App\Dispenser\Application\UseCase\Create\CreateDispenserUseCaseRequest;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class CreateDispenserUseCaseTest extends TestCase
{
    private CreateDispenserUseCase       $classUnderTest;
    private DispenserRepositoryInterface $dispenserRepositoryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->dispenserRepositoryMock = $this->createMock(DispenserRepositoryInterface::class);
        $this->classUnderTest          = new CreateDispenserUseCase($this->dispenserRepositoryMock);
    }

    public function testExecuteShouldCreateDispenser(): void
    {
        //Given
        $flowVolume = 1;
        $price      = 1.0;
        $promoterId = 1;

        $request = new CreateDispenserUseCaseRequest(
            $flowVolume,
            $price,
            $promoterId
        );

        $this->dispenserRepositoryMock
            ->expects($this->once())
            ->method('save');

        $response = $this->classUnderTest->execute($request);

        $this->assertTrue($response->isValid());
    }
}
