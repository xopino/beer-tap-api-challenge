<?php

namespace App\Dispenser\Domain\Persistence\Repository;

use App\Dispenser\Domain\Entity\DispenserSpendingLine;

interface DispenserSpendingLineRepositoryInterface
{
    public function findById(string $id): ?DispenserSpendingLine;

    public function save(DispenserSpendingLine $dispenser): void;

    public function searchByDispenserId(string $dispenserId): array;

    public function findOpenDispenserSpendingLine(string $dispenserId): ?DispenserSpendingLine;
}
