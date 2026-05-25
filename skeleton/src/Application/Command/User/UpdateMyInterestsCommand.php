<?php

declare(strict_types=1);

namespace App\Application\Command\User;

use App\Application\Command\CommandInterface;

final readonly class UpdateMyInterestsCommand implements CommandInterface
{
    /**
     * @param array<string> $interestIds
     */
    public function __construct(
        public array $interestIds,
        public string $token = '',
    ) {
    }
}
