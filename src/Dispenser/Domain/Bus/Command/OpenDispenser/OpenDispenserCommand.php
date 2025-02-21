<?php

namespace App\Dispenser\Domain\Bus\Command\OpenDispenser;

use App\Shared\Domain\Bus\Command\Command;

class OpenDispenserCommand implements Command
{
    public function __construct(
        public readonly string $dispenserId,
        public readonly int    $attendeeId
    )
    {
    }
}
