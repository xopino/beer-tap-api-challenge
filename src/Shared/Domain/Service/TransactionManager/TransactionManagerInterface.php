<?php

namespace App\Shared\Domain\Service\TransactionManager;

interface TransactionManagerInterface
{
    public function beginTransaction(): void;

    public function commit(): void;

    public function rollback(): void;
}
