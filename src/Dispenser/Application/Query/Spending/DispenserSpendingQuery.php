<?php

namespace App\Dispenser\Application\Query\Spending;

use App\Shared\Domain\Query\Query;

class DispenserSpendingQuery implements Query
{
    public function __construct(
        public readonly string $dispenserId,
    )
    {
    }
}
