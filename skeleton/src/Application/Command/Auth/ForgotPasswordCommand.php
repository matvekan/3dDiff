<?php

declare(strict_types=1);

namespace App\Application\Command\Auth;

use App\Application\Command\CommandInterface;

final readonly class ForgotPasswordCommand implements CommandInterface
{
    public function __construct(public string $email)
    {
    }
}
