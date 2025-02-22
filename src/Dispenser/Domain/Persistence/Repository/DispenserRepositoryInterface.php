<?php

namespace App\Dispenser\Domain\Persistence\Repository;

use App\Dispenser\Domain\Entity\Dispenser;

interface DispenserRepositoryInterface
{
    public function findById(string $id): ?Dispenser;
    public function save(Dispenser $dispenser): void;
}
