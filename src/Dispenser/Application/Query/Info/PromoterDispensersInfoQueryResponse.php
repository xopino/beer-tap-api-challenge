<?php

namespace App\Dispenser\Application\Query\Info;

class PromoterDispensersInfoQueryResponse
{
    public function __construct(
        public readonly array $dispenserInfo,
        public readonly int   $totalOfServices,
        public readonly int   $totalSecondsElapsed,
        public readonly float $totalMoneySpent
    )
    {
    }
}
