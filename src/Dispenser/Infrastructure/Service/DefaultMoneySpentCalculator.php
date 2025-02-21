<?php

namespace App\Dispenser\Infrastructure\Service;

use App\Dispenser\Domain\Service\MoneySpentCalculator;

class DefaultMoneySpentCalculator implements MoneySpentCalculator
{
    public function calculate(string $startDate, string $endDate, float $flowVolume, float $price): float
    {
        $start = new \DateTime($startDate);
        $end   = new \DateTime($endDate);

        if ($start == $end) {
            throw new \Exception('Start date and end date cannot be the same.');
        }

        if ($end < $start) {
            throw new \Exception('End date must be greater than start date.');
        }

        $seconds = $end->getTimestamp() - $start->getTimestamp();
        $litersConsumed = $flowVolume * $seconds;

        return round($litersConsumed * $price, 2);
    }
}
