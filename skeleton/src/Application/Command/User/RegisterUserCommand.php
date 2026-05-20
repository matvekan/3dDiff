<?php

declare (strict_types = 1);

namespace App\Application\Command\User;

use App\Application\Command\CommandInterface;

final readonly class RegisterUserCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $password,
    )
    {}

}
