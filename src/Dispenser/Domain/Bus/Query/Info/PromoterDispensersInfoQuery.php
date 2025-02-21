<?php

namespace App\Dispenser\Domain\Bus\Query\Info;

use App\Shared\Domain\Bus\Query\Query;

class PromoterDispensersInfoQuery implements Query
{
    public function __construct(
        public readonly int $promoterId
    )
    {
    }
}
