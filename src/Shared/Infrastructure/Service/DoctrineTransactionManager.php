<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Domain\Service\TransactionManager\TransactionManagerInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineTransactionManager implements TransactionManagerInterface
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function beginTransaction(): void
    {
        $this->entityManager->getConnection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->entityManager->getConnection()->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->getConnection()->rollBack();
    }
}
