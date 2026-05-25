<?php

declare(strict_types=1);

namespace App\Application\Command\Admin;

use App\Application\Command\CommandInterface;

final readonly class UpdateUserCommand implements CommandInterface
{
    /**
     * @param array<string>|null $interestIds
     */
    public function __construct(
        public ?string $name = null,
        public ?string $role = null,
        public ?array $interestIds = null,
        public string $token = '',
        public string $userId = '',
    ) {
    }
}
