<?php

namespace App\Dispenser\Application\Query\Info;

class ServiceInfoDTO
{
    public function __construct(
        public readonly string $secondsElapsed,
        public readonly float  $moneySpent
    )
    {
    }
}
