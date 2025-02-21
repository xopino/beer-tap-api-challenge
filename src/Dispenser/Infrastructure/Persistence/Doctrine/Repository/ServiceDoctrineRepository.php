<?php

namespace App\Dispenser\Infrastructure\Persistence\Doctrine\Repository;

use App\Dispenser\Domain\Entity\Service;
use App\Dispenser\Domain\Persistence\Repository\ServiceRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Doctrine\AbstractDoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

class ServiceDoctrineRepository extends AbstractDoctrineRepository implements ServiceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    public function findById(string $id): ?Service
    {
        return $this->find($id);
    }
}
