<?php

namespace App\Dispenser\Domain\Bus\Command\CloseDispenser;

use App\Shared\Domain\Bus\Command\Command;

class CloseDispenserCommand implements Command
{
    public function __construct(
        public readonly string $dispenserId
    )
    {
    }
}
