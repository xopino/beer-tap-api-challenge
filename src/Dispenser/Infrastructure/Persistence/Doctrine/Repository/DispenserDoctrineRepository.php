<?php

namespace App\Dispenser\Infrastructure\Persistence\Doctrine\Repository;

use App\Dispenser\Domain\Entity\Dispenser;
use App\Dispenser\Domain\Persistence\Repository\DispenserRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Doctrine\AbstractDoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

class DispenserDoctrineRepository extends AbstractDoctrineRepository implements DispenserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dispenser::class);
    }

    public function findById(string $id): ?Dispenser
    {
        return $this->find($id);
    }
}
