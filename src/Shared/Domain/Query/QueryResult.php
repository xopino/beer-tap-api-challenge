<?php

namespace App\Shared\Domain\Query;

class QueryResult
{
    public function __construct(
        public readonly mixed $result
    )
    {
    }
}
