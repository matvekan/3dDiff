<?php

declare(strict_types=1);

namespace App\Application\Command\Interest;

use App\Application\Command\CommandInterface;

final readonly class CreateInterestCommand implements CommandInterface
{
    public function __construct(
        public string $name,
        public string $token = '',
    ) {}
}
