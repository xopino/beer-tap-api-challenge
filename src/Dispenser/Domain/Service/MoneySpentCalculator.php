<?php

namespace App\Dispenser\Domain\Service;

interface MoneySpentCalculator
{
    public function calculate(
        string $startDate,
        string $endDate,
        float  $flowVolume
    ): float;
}
