<?php

declare(strict_types=1);

namespace App\Application\Command\Auth;

use App\Application\Command\CommandInterface;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ForgotPasswordCommand implements CommandInterface
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,
    ) {
    }
}
