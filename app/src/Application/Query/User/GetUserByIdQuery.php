<?php

namespace App\Application\Query\User;

use App\Application\Query\QueryInterface;

class GetUserByIdQuery implements QueryInterface
{
    public function __construct(
        public string $id,
    ) {}
}
