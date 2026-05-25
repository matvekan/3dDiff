<?php

declare(strict_types=1);

namespace App\Application\Command\Auth;

use App\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class LoginCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
        #[Assert\NotBlank]
        #[Assert\Length(min: 6, max: 255)]
        public string $password,
    ) {
    }
}
