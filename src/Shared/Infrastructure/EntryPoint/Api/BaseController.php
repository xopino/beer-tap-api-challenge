<?php

namespace App\Shared\Infrastructure\EntryPoint\Api;

use App\Shared\Domain\Command\Command;
use App\Shared\Domain\Command\CommandBusInterface;
use App\Shared\Domain\Query\QueryBusInterface;
use App\Shared\Domain\Query\QueryResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface   $queryBus,
        private readonly CommandBusInterface $commandBus
    )
    {
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }

    public function ask($query): QueryResult
    {
        return $this->queryBus->dispatch($query);
    }
}
