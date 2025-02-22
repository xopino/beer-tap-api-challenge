<?php

namespace App\Dispenser\Application\Query\Spending;

class DispenserSpendingQueryResponse
{
    public function __construct(
        public readonly float $amount,
        public readonly array $usages
    )
    {
    }
}
