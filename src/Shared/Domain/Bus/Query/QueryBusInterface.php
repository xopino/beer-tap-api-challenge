<?php

namespace App\Shared\Domain\Bus\Query;

interface QueryBusInterface
{
    public function dispatch(Query $query): QueryResult;
}
