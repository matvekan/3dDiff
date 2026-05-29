<?php
declare(strict_types=1);

namespace App\Application\Command\Admin;

use App\Application\Command\CommandInterface;
use App\Domain\ValueObject\Role;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserCommand implements CommandInterface
{
    public function __construct(
        #[Assert\Length(min: 2, max: 50)]
        public ?string $name = null,
        #[Assert\Choice(choices: [Role::USER->value, Role::ADMIN->value])]
        public ?string $role = null,
        /** @var array<string>|null */
        #[Assert\Type('array')]
        #[Assert\All([new Assert\Uuid()])]
        public ?array $interestIds = null,
        #[Assert\Uuid]
        public string $userId = '',
    ) {
    }
}
