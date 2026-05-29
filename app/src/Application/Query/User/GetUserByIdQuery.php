<?php

declare(strict_types=1);

namespace App\Application\Query\User;

use App\Application\Query\QueryInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetUserByIdQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Uuid]
        public string $id,
    ) {
    }
}
