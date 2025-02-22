<?php

namespace App\Dispenser\Application\Command\ChangeStatus;

use App\Shared\Domain\Command\Command;

class ChangeStatusCommand implements Command
{
    public function __construct(
        public readonly string $dispenserId,
        public readonly string $status,
        public readonly string $updatedAt
    )
    {
    }
}
