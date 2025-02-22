<?php

namespace App\Shared\Domain\Query;

interface QueryBusInterface
{
    public function dispatch(Query $query): QueryResult;
}
