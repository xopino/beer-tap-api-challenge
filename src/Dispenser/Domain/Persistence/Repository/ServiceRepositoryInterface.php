<?php

namespace App\Dispenser\Domain\Persistence\Repository;

use App\Dispenser\Domain\Entity\Service;

interface ServiceRepositoryInterface
{
    public function findById(string $id): ?Service;

    public function save(Service $dispenser): void;

    public function searchByDispenserId(string $dispenserId): array;

    public function findOpenServiceByDispenserId(string $dispenserId): ?Service;
}
