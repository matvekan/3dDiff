<?php

declare(strict_types=1);

namespace App\Application\Query\User;

use App\Application\Query\QueryInterface;

final readonly class GetMyProfileQuery implements QueryInterface
{
    public function __construct(public string $token)
    {
    }
}
