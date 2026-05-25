<?php

declare(strict_types=1);

namespace App\Application\Command\Admin;

use App\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserCommand implements CommandInterface
{
    /**
     * @param array<string>|null $interestIds
     */
    public function __construct(
        #[Assert\Length(min: 2, max: 50)]
        public ?string $name = null,
        #[Assert\Choice(choices: ['ROLE_USER', 'ROLE_ADMIN'])]
        public ?string $role = null,
        #[Assert\Type('array')]
        #[Assert\All([
            new Assert\Uuid(),
        ])]
        public ?array $interestIds = null,
        public string $token = '',
        public string $userId = '',
    ) {
    }
}
