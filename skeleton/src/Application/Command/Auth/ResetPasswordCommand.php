<?php

declare(strict_types=1);

namespace App\Application\Command\Auth;

use App\Application\Command\CommandInterface;

final readonly class ResetPasswordCommand implements CommandInterface
{
    public function __construct(
        public string $token,
        public string $newPassword,
    ) {
    }
}
