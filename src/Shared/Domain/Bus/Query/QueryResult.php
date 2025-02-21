<?php

namespace App\Shared\Domain\Bus\Query;

class QueryResult
{
    public function __construct(
        public readonly mixed $result
    )
    {
    }
}
