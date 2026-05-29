<?php

declare(strict_types=1);

namespace App\Application\Query\Admin;

use App\Application\Query\QueryInterface;
use App\Domain\ValueObject\Role;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ListUsersQuery implements QueryInterface
{
    public function __construct(
        #[Assert\Length(min: 0, max: 50)]
        public string $name = '',
        #[Assert\Email]
        public string $email = '',
        #[Assert\Choice(choices: [Role::USER->value, Role::ADMIN->value, ''])]
        public string $role = '',
        #[Assert\Length(min: 0, max: 255)]
        public string $interest = '',
    ) {
    }
}
