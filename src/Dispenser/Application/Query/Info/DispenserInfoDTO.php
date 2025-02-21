<?php

namespace App\Dispenser\Application\Query\Info;

class DispenserInfoDTO
{
    public function __construct(
        public readonly int   $totalOfServices,
        public readonly float $totalMoneySpent,
        public readonly array $servicesInfo
    )
    {
    }
}
