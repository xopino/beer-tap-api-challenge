<?php

namespace App\Dispenser\Application\UseCase\Create;

class CreateDispenserUseCaseRequest
{
    public function __construct(
        public readonly int   $flowVolume,
        public readonly float $price,
        public readonly int   $promoterId
    )
    {
    }
}
