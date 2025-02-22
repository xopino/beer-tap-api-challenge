<?php

namespace App\Dispenser\Application\UseCase\Create;

class CreateDispenserUseCaseRequest
{
    public function __construct(
        public readonly float $flowVolume,
    )
    {
    }
}
