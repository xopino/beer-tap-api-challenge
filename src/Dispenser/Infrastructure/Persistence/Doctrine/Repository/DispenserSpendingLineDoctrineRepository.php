<?php

namespace App\Dispenser\Infrastructure\Persistence\Doctrine\Repository;

use App\Dispenser\Domain\Entity\DispenserSpendingLine;
use App\Dispenser\Domain\Persistence\Repository\DispenserSpendingLineRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Doctrine\AbstractDoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

class DispenserSpendingLineDoctrineRepository extends AbstractDoctrineRepository implements DispenserSpendingLineRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DispenserSpendingLine::class);
    }

    public function findById(string $id): ?DispenserSpendingLine
    {
        return $this->find($id);
    }

    public function searchByDispenserId(string $dispenserId): array
    {
        return $this->findBy(['dispenserId' => $dispenserId]);
    }


    public function findOpenDispenserSpendingLine(string $dispenserId): ?DispenserSpendingLine
    {
        return $this->findOneBy([
                                    'dispenserId' => $dispenserId,
                                    'closedAt'    => null
                                ]);
    }
}
