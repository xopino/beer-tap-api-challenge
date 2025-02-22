<?php

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Query\Query;
use App\Shared\Domain\Query\QueryBusInterface;
use App\Shared\Domain\Query\QueryResult;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SymfonyMessengerQueryBus implements QueryBusInterface
{
    public function __construct(
        private MessageBusInterface $queryBus
    )
    {
    }

    public function dispatch(Query $query): QueryResult
    {
        $envelope = new Envelope($query);
        $result   = $this->queryBus->dispatch($envelope);

        $handled = $result->last(HandledStamp::class);

        return new QueryResult($handled->getResult());
    }
}
