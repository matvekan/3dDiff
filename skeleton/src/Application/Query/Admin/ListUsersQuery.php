<?php

declare(strict_types=1);

namespace App\Application\Query\Admin;

use App\Application\Query\QueryInterface;

final readonly class ListUsersQuery implements QueryInterface
{
    public function __construct(
        public string $token,
        public string $name,
        public string $email,
        public string $role,
        public string $interest,
    ) {
    }
}
